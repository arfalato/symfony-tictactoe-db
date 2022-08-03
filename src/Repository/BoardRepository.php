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
    
    const EMPTY_GRID = [
        [null, null, null],
        [null, null, null],
        [null, null, null]
    ];
    
    const FIRST_MOVE = 'X';
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    public function add(Board $entity): void
    {
        $entity->setGrid(self::EMPTY_GRID);
        $entity->setTurn(self::FIRST_MOVE);
        $entity->setDate(new \DateTime());
        
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function update(int $id, array $params): array
    {
        $entity = $this->findBoard($id);
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
        $this->getEntityManager()->flush();
       
        
        return $result;
    }
    
    public function delete(int $id) : array
    {
        $boardToFind = $this->findBoard($id);
        
        if (is_null($boardToFind)) {
            return ['error' => 'No Board found for id ' . $id];
        }
        
        $this->removeBoard($boardToFind);
        
        return ['id' => $id];
    }
    
    public function findBoard($id) : ?Board
    {
        return $this->getEntityManager()->find(Board::class, $id);
    }
    
    private function removeBoard(Board $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();   
    }
}