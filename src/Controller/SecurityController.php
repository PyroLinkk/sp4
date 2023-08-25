<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(#[CurrentUser] User $user = null): JsonResponse
    {
        $message = "The server is running.";
        if ($user) {
            $message .= "\n And you are logged in.";
        }
        return $this->json([
            'message' => $message,
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] User $user = null): JsonResponse
    {
        return $this->json([
            'user' => $user ? $user->getId() : null,
        ]);
    }

    #[Route('/me', name: 'app_me')]
    public function get_me(#[CurrentUser] User $user) : JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->json( $user, 200, [], ["groups" => "user:me"]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception("This can't be reached.");
    }
}
