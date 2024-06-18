<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    #[\Symfony\Component\Routing\Attribute\Route('/profile', name: 'profile', methods: ['GET'])]
    public function show(): Response
    {
        if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
        $user = $this->users->findDetail($this->getUser()->getId());

        return $this->render('app/profile/show.html.twig',$user->toArray());
    }
}
