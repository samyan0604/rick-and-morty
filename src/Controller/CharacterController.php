<?php

namespace App\Controller;

use App\Service\RickMortyApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    /**
     * Character list page - publicly accessible
     */
    #[Route('/characters', name: 'app_character_list')]
    public function list(Request $request, RickMortyApiService $apiService): Response
    {
        // Get page number from URL (?page=2), default to 1
        $page = max(1, $request->query->getInt('page', 1));
        
        // Get characters from API service
        $data = $apiService->getCharacters($page);
        
        // Extract characters and pagination info
        $characters = $data['characters'] ?? [];
        $info = $data['info'] ?? [];
        $error = $data['error'] ?? null;
        
        // Calculate pagination details
        $currentPage = $page;
        $totalPages = $info['pages'] ?? 1;
        $hasNext = $currentPage < $totalPages;
        $hasPrevious = $currentPage > 1;
        
        return $this->render('character/list.html.twig', [
            'characters' => $characters,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'hasNext' => $hasNext,
            'hasPrevious' => $hasPrevious,
            'totalCount' => $info['count'] ?? 0,
            'error' => $error,
        ]);
    }

    /**
     * Single character page - protected (requires login)
     */
    #[Route('/characters/{id}', name: 'app_character_show', requirements: ['id' => '\d+'])]
    public function show(int $id, RickMortyApiService $apiService): Response
    {
        // This route will be protected by security.yaml (requires ROLE_USER)
        
        // Get single character from API service
        $character = $apiService->getCharacter($id);
        
        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }
        
        return $this->render('character/show.html.twig', [
            'character' => $character,
        ]);
    }
}