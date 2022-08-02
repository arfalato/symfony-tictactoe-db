<?php

namespace App\Repository;

use App\Entity\Board;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BoardRepository extends ServiceEntityRepository
{
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

    public function delete(int $id)
    {
        $boardToFind = $this->getEntityManager()->find(Board::class, $id);
        if (!$boardToFind) {
            return ['error' => 'No Board found for id ' . $id];
        }
        $this->getEntityManager()->remove($boardToFind);
        $this->getEntityManager()->flush();
        return $id;
    }
    
    public function findBoard($id)
    {
        return $this->getEntityManager()->find(Board::class, $id);
    }
    
    public function remove(Board $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
