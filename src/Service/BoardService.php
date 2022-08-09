<?php

namespace App\Service;

use App\Repository\BoardRepository;
use App\Entity\Board;

class BoardService
{
    private BoardRepository $repo;
    
    public function __construct(BoardRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function post(Board $board)
    {
        return $this->repo->add($board);
    }
    
    public function delete(int $id):array
    {
        return $this->repo->delete($id);
    }
    
    public function update(int $id, array $payload):array
    {
        return $this->repo->update($id, $payload);
    }
}