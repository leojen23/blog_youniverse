<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    #[Route('/api/', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('/api/post', name: 'api_post', methods: ['GET'])]
    public function getAllPosts(PostRepository $postRepository, SerializerInterface $serializer, UserRepository $userRepository): JsonResponse{

        
        $posts =$postRepository->findAll();

        foreach ($posts as $post) {
        $user = $userRepository->findOneBy(["id" => $post->getUser()->getId()]);
        // dd($user);
        $data[] = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'createdAt' => $post->getCreatedAt(),
            'updatedAt' => $post->getUpdatedAt(),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
            ],
            'category' => [
                'id' => $post->getCategory()->getId(),
                'name' => $post->getCategory()->getName()]
        ];
    }
        return new JsonResponse($data);
    }
}
