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
    
    public function findAll(): array
    {
        $findAll = $this->repo->findAll();

        $message = [
            'output' => ['message' => 'No Board found'],
            'status' => self::NOT_FOUND,
        ];
        
        if (empty($findAll)) {
            return $message;
        }

        return [
            'output' => $findAll,
            'status' => self::OK,
        ];
    }
    
    public function post(): array
    {
        $add = $this->repo->add($this->board);
        
        $add['output'] = ['Board' => $this->board->getGrid(), 'id' => $this->board->getId()];
        $add['status'] = self::OK; 
        
        return $add;
    }
    
    public function delete(int $id): array
    {
        $deleted = $this->repo->delete($id);
        
        if(isset($deleted['error'])) {
            
            $deleted['output'] = ['error' => 'No Board found for id ' . $id];
            $deleted['status'] = self::NOT_FOUND;
            
            return $deleted;            
        }
        
        $deleted['output'] = ['Board' => 'deleted', 'id' => $id];
        $deleted['status'] = self::OK; 
        
        return $deleted;
    }
    
    public function update(int $id, array $payload): array
    {
        $validator = $this->validator->validateParams($payload);
        
        if (count($validator['error']) > 0) {
            
            $actualBoard = $this->repo->findBoard($id);
            if(is_null($actualBoard)) {
                $update['output'] = ['error' => 'No payload and Board not found'];
                $update['status'] = self::BAD_REQUEST;
                return $update;
            }
            $update['output'] = $validator + ['Board' => $actualBoard->getGrid()];
            $update['status'] = self::BAD_REQUEST;
            
            return $update;
        }

        return $this->repo->update($id, $payload);
    }
}
