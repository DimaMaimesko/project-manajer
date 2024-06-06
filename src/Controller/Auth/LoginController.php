<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\Login;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class LoginController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    #[Route('/login', name: 'auth.login', methods: ['GET', 'POST'])]
    public function request(Request $request, Login\Request\Handler $handler): Response
    {
        $command = new Login\Request\Command();

        $form = $this->createForm(Login\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'You are Logged in!!!');
                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/signup/{token}', name: 'auth.signup.confirm', methods: ['GET', 'POST'])]
    public function confirm(string $token, SignUp\Confirm\Handler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);
        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully confirmed.');
            return $this->redirectToRoute('home');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');
        }
    }
}
