<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BooksRepository;

final class LibaryController extends AbstractController
{
    #[Route('/libary', name: 'libary')]
    public function showAllBooks(
        BooksRepository $BooksRepository
    ): response {
        $books = $BooksRepository
            ->findAll();

        $data = [
            "books" => $books
        ];

        return $this->render('libary/index.html.twig', $data);
    }

    #[Route('/libary/form', name: 'libary_form')]
    public function showForm(): Response
    {
        return $this->render('libary/form.html.twig');
    }

    #[Route('/libary/add', name: 'libary_add')]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {

        $entityManager = $doctrine->getManager();
        $book = new Books();

        $book->setTitle($request->request->get('title'));
        $book->setAuthor($request->request->get('author'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setImg($request->request->get('img'));

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('libary');
        
    }

    
}
