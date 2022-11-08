<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HelloController extends AbstractController
{
    #[Route('/hello/{name<[a-zA-Z- ]+>?World}', name: 'app_hello')]
    public function index(string $name, ValidatorInterface $validator): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
