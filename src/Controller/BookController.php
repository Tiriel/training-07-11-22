<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/{id<\d+>?1}', name: 'app_book_details', methods: ['GET', 'POST'])]
    public function details(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => $id,
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($book, null, ['Book']);
            $this->denyAccessUnlessGranted(BookVoter::VIEW, $book);
        }

        return $this->renderForm('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
