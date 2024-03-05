<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function toggleLike(Article $article, User $user): int
    {
        $likesList = $article->getLikesList() ?? [];

        // Check if the user has already liked the article
        $userLiked = in_array($user->getId(), $likesList);

        if ($userLiked) {
            // User has already liked the article, so remove the like
            $likesList = array_diff($likesList, [$user->getId()]);
        } else {
            // User has not liked the article, so add the like
            $likesList[] = $user->getId();
        }

        // Update the article entity with the new likes list and count
        $article->setLikesList($likesList);
        $article->setNbLikes(count($likesList));

        // Persist the changes to the database
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        // Return the updated number of likes for the article
        return count($likesList);
    }
}
