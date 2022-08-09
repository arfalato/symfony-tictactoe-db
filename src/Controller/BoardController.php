<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BoardService;
use App\Entity\Board;


/**
 * @Route("/api", name="api_")
 */
class BoardController extends AbstractController
{
    
    
     
    private ManagerRegistry $doctrine;
    
    private BoardRepository $repo;
     
    public function __construct(BoardService $service)
    {
         $this->service = $service;
    }
    
   /**
     * @Route("/board", name="board_post", methods={"POST"})
     */
    public function post(): JsonResponse
    {
        $create = $this->service->post();
        return $this->json($create['message'], $create['status']);
    }
   
   /**
     * @Route("/board/{id}", name="board_delete", methods={"DELETE"})
     */
    public function delete(int $id) : JsonResponse
    {
        $deleted = $this->service->delete($id);
        return $this->json($deleted['message'], $deleted['status']);
    }
    
    /**
     * @Route("/board/{id}", name="board_put", methods={"PUT"})
     */
    public function put(int $id, Request $request): JsonResponse
    {
        $payload = json_decode((string) $request->getContent(), true);
        $move = $this->service->update($id, (array) $payload);
        
        return $this->json($move['message'], (int) $move['status']);
    }
}