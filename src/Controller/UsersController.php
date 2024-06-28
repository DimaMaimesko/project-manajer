<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User\Entity\User\User;
use App\ReadModel\User\UserFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\User\UseCase\Create;
use App\Model\User\UseCase\Edit;
use App\Model\User\UseCase\Role;


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

    #[Route('users/{id}/edit', name: 'users.edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromUser($user);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    #[Route('users/create', name: 'users.create', methods: ['GET', 'POST'])]
    public function create(Request $request, Create\Handler $handler, LoggerInterface $logger): Response
    {
        $command = new Create\Command();

        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('users');
            } catch (\DomainException $e) {
                $logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/users/{id}/role', name: 'users.role', methods: ['GET', 'POST'])]
    public function role(User $user, Request $request, Role\Handler $handler, LoggerInterface $logger): Response
    {

        if ($user->getId()->getValue() === $this->getUser()->getId()->getValue()) {
            $this->addFlash('error', 'Unable to change role for yourself.');
            return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
        }

        $command = Role\Command::fromUser($user);

        $form = $this->createForm(Role\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/users/role.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/users/{id}', name: 'users.show', methods: ['GET'])]
    public function show(User $user): Response
    {

        return $this->render('app/users/show.html.twig', compact('user'));
    }





}
