<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$passwordEncoder->isPasswordValid($user, $data['password'])) {
            throw new BadCredentialsException('Invalid credentials');
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token,
        ]);
    }
}

