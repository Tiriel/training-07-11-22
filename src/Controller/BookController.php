<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book_index')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/{id<\d+>?1}', name: 'app_book_details', methods: ['GET', 'POST'])]
    public function details($id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => $id,
        ]);
    }
}
