<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User\Entity\User\User;
use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class UsersController extends AbstractController
{
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    #[Route('/users', name: 'users', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->users->all();

        return $this->render('app/users/index.html.twig', compact('users'));
    }

    #[Route('/users/{id}', name: 'users.show', methods: ['GET'])]
    public function show(User $user): Response
    {

        return $this->render('app/users/show.html.twig', compact('user'));
    }
}
