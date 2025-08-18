<?php

namespace App\Controller;

use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoriteController extends AbstractController
{
    /**
     * User favorites page - shows all characters the user has favorited
     * This route is protected by security.yaml (requires ROLE_USER)
     */
    #[Route('/user/characters', name: 'app_user_favorites')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Get the current logged-in user
        $user = $this->getUser();
        
        if (!$user) {
            // This shouldn't happen due to security.yaml, but just in case
            $this->addFlash('error', 'You must be logged in to view your favorites.');
            return $this->redirectToRoute('app_login');
        }

        // Get all favorites for this user, ordered by most recently added
        $favorites = $entityManager->getRepository(Favorite::class)
            ->findBy(['user' => $user]);

        return $this->render('favorite/list.html.twig', [
            'favorites' => $favorites,
            'user' => $user,
        ]);
    }

    /**
     * Remove a character from favorites (alternative route from favorites page)
     */
    #[Route('/user/characters/{id}/remove', name: 'app_favorite_remove', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function remove(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Verify CSRF token for security
        if (!$this->isCsrfTokenValid('remove_favorite' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid security token. Please try again.');
            return $this->redirectToRoute('app_user_favorites');
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

        return $this->redirectToRoute('app_user_favorites');
    }
}