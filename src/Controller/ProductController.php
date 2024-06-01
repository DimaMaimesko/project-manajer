<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route(  '/product', name: 'app_product', methods: ['GET'])]
    public function index(): Response
    {
       return $this->render('app/home.html.twig', []);
    }
}
