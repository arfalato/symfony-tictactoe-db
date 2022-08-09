<?php

namespace App\Service;

use App\Repository\BoardRepository;
use App\Entity\Board;
use App\Validator\BoardValidator;

class BoardService
{
    private BoardRepository $repo;
    private BoardValidator $validator;
    private Board $board;
    
    const OK = 200;
    const NOT_FOUND = 404;
    const BAD_REQUEST = 400;
    
    public function __construct(BoardRepository $repo, BoardValidator $validator, Board $board)
    {
        $this->repo = $repo;
        $this->validator = $validator;
        $this->board = $board;
    }
    
    public function findAll()
    {
        $find = $this->repo->findAll();
        
        if (empty($find)) {
            
            $find['message'] = ['No board found'];
            $find['status'] = self::NOT_FOUND ;
            
            return $find;
        }
        
        $find['message'] = $find;
        $find['status'] = self::OK;
        
        return $find;
    }
    
    public function post() : array
    {
        $add = $this->repo->add($this->board);
        
        $add['message'] = ['Board' => $this->board->getGrid(), 'id' => $this->board->getId()];
        $add['status'] = self::OK; 
        
        return $add;
    }
    
    public function delete(int $id) : array
    {
        $deleted = $this->repo->delete($id);
        
        if(isset($deleted['error'])) {
            
            $deleted['message'] = ['error' => 'No Board found for id ' . $id];
            $deleted['status'] = self::NOT_FOUND;
            
            return $deleted;            
        }
        
        $deleted['message'] = ['Board' => 'deleted', 'id' => $id];
        $deleted['status'] = self::OK; 
        
        return $deleted;
    }
    
    public function update(int $id, array $payload) : array
    {
        $validator = $this->validator->validateParams((array) $payload);
        
        if (count($validator['error']) > 0) {
            
            $actualBoard = $this->repo->findBoard($id);
            
            $update['message'] = $validator + ['Board' => $actualBoard->getGrid()];
            $update['status'] = self::BAD_REQUEST;
            
            return $update;
        }
        
        $update = $this->repo->update($id, $payload);
        
        return $update;
    }
}
