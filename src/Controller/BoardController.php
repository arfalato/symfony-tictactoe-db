<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BoardRepository;
use App\Entity\Board;
use App\Validator\BoardValidator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/api", name="api_")
 */
class BoardController extends AbstractController
{
    
    const OK = 200;
    
    const BAD_REQUEST = 400;
    
    const NOT_FOUND = 404;
    
    private Board $board;
     
    private ManagerRegistry $doctrine;
    
    private BoardValidator $validator;
     
     
    public function __construct(Board $board, ManagerRegistry $doctrine, BoardValidator $validator)
    {
        $this->board = $board;
        $this->doctrine = $doctrine;
        $this->validator = $validator;
        $this->repo = new BoardRepository($this->doctrine);
    }
    
   /**
     * @Route("/board", name="board_post", methods={"POST"})
     */
    public function post(): JsonResponse
    {
        $this->repo->add($this->board);
        return $this->json(['Board' => $this->board->getGrid(), 'id' => $this->board->getId()], self::OK);
    }
   
   /**
     * @Route("/board/{id}", name="board_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->repo->delete($id);
        
        if(isset($deleted['error'])) {
            return $this->json($deleted, self::NOT_FOUND);            
        }
        
        return $this->json(['Board' => 'deleted', 'id' => $deleted['id']], self::OK);
    }
    
    /**
     * @Route("/board/{id}", name="board_put", methods={"PUT"})
     */
    public function put(int $id, Request $request): JsonResponse
    {
        $payload = json_decode((string) $request->getContent(), true);
        $validator = $this->validator->validateParams((array) $payload);

        if (count($validator['error']) > 0) {
            $actualBoard = $this->repo->findBoard($id);
            return $this->json($validator + ['Board' => $actualBoard->getGrid()], self::BAD_REQUEST);
        }
        
        $move = $this->repo->update($id, $payload);
        
        return $this->json($move['grid'], (int) $move['status']);
    }
}
