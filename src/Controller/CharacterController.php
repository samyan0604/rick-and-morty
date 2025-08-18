<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Service\RickMortyApiService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function show(int $id, RickMortyApiService $apiService, EntityManagerInterface $entityManager): Response
    {
        // This route is protected by security.yaml (requires ROLE_USER)
        
        // Get single character from API service
        $character = $apiService->getCharacter($id);
        
        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }

        // Check if this character is already in user's favorites
        $user = $this->getUser();
        $isFavorite = false;
        
        if ($user) {
            $existingFavorite = $entityManager->getRepository(Favorite::class)
                ->findOneBy([
                    'user' => $user,
                    'characterId' => $id
                ]);
            $isFavorite = $existingFavorite !== null;
        }
        
        return $this->render('character/show.html.twig', [
            'character' => $character,
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * Add character to user's favorites
     */
    #[Route('/characters/{id}/favorite', name: 'app_character_add_favorite', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function addToFavorites(int $id, Request $request, RickMortyApiService $apiService, EntityManagerInterface $entityManager): Response
    {
        // Verify CSRF token for security
        if (!$this->isCsrfTokenValid('add_favorite' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid security token. Please try again.');
            return $this->redirectToRoute('app_character_show', ['id' => $id]);
        }

        // Get the current user
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to add favorites.');
            return $this->redirectToRoute('app_login');
        }

        // Check if character exists in API
        $character = $apiService->getCharacter($id);
        if (!$character) {
            $this->addFlash('error', 'Character not found.');
            return $this->redirectToRoute('app_character_list');
        }

        // Check if already in favorites
        $existingFavorite = $entityManager->getRepository(Favorite::class)
            ->findOneBy([
                'user' => $user,
                'characterId' => $id
            ]);

        if ($existingFavorite) {
            $this->addFlash('error', 'This character is already in your favorites!');
        } else {
            // Create new favorite
            $favorite = new Favorite();
            $favorite->setUser($user);
            $favorite->setCharacterId($id);
            $favorite->setCharacterName($character['name']);
            $favorite->setCharacterImage($character['image']);

            $entityManager->persist($favorite);
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s has been added to your favorites!', $character['name']));
        }

        return $this->redirectToRoute('app_character_show', ['id' => $id]);
    }

    /**
     * Remove character from user's favorites
     */
    #[Route('/characters/{id}/unfavorite', name: 'app_character_remove_favorite', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function removeFromFavorites(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verify CSRF token for security
        if (!$this->isCsrfTokenValid('remove_favorite' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid security token. Please try again.');
            return $this->redirectToRoute('app_character_show', ['id' => $id]);
        }

        // Get the current user
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to manage favorites.');
            return $this->redirectToRoute('app_login');
        }

        // Find and remove the favorite
        $favorite = $entityManager->getRepository(Favorite::class)
            ->findOneBy([
                'user' => $user,
                'characterId' => $id
            ]);

        if ($favorite) {
            $characterName = $favorite->getCharacterName();
            $entityManager->remove($favorite);
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s has been removed from your favorites.', $characterName));
        } else {
            $this->addFlash('error', 'This character is not in your favorites.');
        }

        return $this->redirectToRoute('app_character_show', ['id' => $id]);
    }
}