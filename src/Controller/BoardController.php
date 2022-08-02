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
     
    private Board $board;
     
    private ManagerRegistry $doctrine;
    
    private BoardValidator $validator;
     
     
    public function __construct(Board $board, ManagerRegistry $doctrine, BoardValidator $validator)
    {
        $this->board = $board;
        $this->doctrine = $doctrine;
        $this->validator = $validator;
    }
    
   /**
     * @Route("/board", name="board_post", methods={"POST"})
     */
    public function post(): JsonResponse
    {
        $repo = new BoardRepository($this->doctrine);
        $repo->add($this->board, true);
        return $this->json(['Board' => unserialize($this->board->getGrid()), 'id' => $this->board->getId()], self::OK);
    }
   
   /**
     * @Route("/board/{id}", name="board_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $repo = new BoardRepository($this->doctrine);
        $deleted = $repo->delete($id);
        if(isset($deleted['error'])) {
            return $this->json($deleted, 404);            
        }
        
        return $this->json(['Board' => 'deleted', 'id' => $deleted], self::OK);
    }
    
    /**
     * @Route("/board", name="board_put", methods={"PUT"})
     */
    public function put(Request $request): Response
    {
        $payload = json_decode((string) $request->getContent(), true);
        $validator = $this->validator->validateParams((array) $payload);

        if (count($validator['error']) > 0) {
            return $this->json($validator + ['Board' => unserialize($this->board->getGrid())], self::BAD_REQUEST);
        }
        
        //$move = $this->service ->move((array) $payload);
        
        //return $this->json($move['grid'], (int) $move['status']);
        return $this->json('ok', 200);
    }
}
