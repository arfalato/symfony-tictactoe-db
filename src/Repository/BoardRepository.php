<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Winner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BoardRepository extends ServiceEntityRepository
{
    const BAD_REQUEST = 400;
    
    const OK = 200;
    
    const NOT_FOUND = 404;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    public function add(Board $entity, bool $flush = false): void
    {
        $entity->setGrid([[null, null, null], [null, null, null], [null, null, null]]);
        $entity->setTurn('X');
        $entity->setDate(new \DateTime());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(int $id, array $params, bool $flush = false): array
    {
        $entity = $this->getEntityManager()->find(Board::class, $id);
        if (!$entity) {
             return [
                 'grid' => ['error' => 'No Board found for id ' . $id, 'Board' => null], 
                 'status' => self::NOT_FOUND
            ];
            
        }
        $row = $params['row'];
        $column = $params['column'];
        $symbol = $params['symbol'];

        $turn = $entity->getTurn();

        if(!empty($turn) && $turn != $symbol) {

            return [
             'grid' => ['error' => "It's not your turn", 'Board' => $entity->getGrid()], 
             'status' => self::BAD_REQUEST
            ];
        }

        $grid = $entity->getGrid();
        
         if(is_null($grid[$row][$column])) {
             
            $grid[$row][$column] = $symbol;
            $entity->setGrid($grid);
            $entity->setTurn($symbol);
            $entity->switchTurn();
            
            $result = [
            'grid' => ['Board' => $entity->getGrid()], 
            'status' => self::OK
            ] ;
        } else {

            return [
            'grid' => ['error'=> "POSITION ALREADY MARKED", 'Board' => $entity->getGrid()], 
            'status' => self::BAD_REQUEST
            ];
        }
        
        
        
        
        $winner = new Winner($entity->getGrid());
        $getWinner = $winner->getWinner();

        $fulfilled = $winner->checkGridFulfilled();
        $draw = $winner->checkDraw($fulfilled);

        if ($getWinner) {

            return [
            'grid' => ['winner' => $getWinner, 'Board' => $entity->getGrid()], 
            'status' => self::OK
            ];
        }

        if($draw) {

            return [
              'grid' => ['winner' => "DRAW", 'Board' => $entity->getGrid()], 
              'status' => self::OK
            ];
        }

        
        $entity->setDate(new \DateTime());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        
        return $result;
    }
    
    public function delete(int $id) : int
    {
        $boardToFind = $this->getEntityManager()->find(Board::class, $id);
        if (!$boardToFind) {
            return ['error' => 'No Board found for id ' . $id];
        }
        $this->getEntityManager()->remove($boardToFind);
        $this->getEntityManager()->flush();
        return $id;
    }
    
    private function findBoard($id) : Board
    {
        return $this->getEntityManager()->find(Board::class, $id);
    }
    
    private function remove(Board $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
