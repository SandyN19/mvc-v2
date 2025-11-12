<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibaryController extends AbstractController
{
    #[Route('/libary', name: 'libary')]
    public function showAllBooks(
        BooksRepository $BooksRepository,
    ): Response {
        $books = $BooksRepository
            ->findAll();

        $data = [
            'books' => $books,
        ];

        return $this->render('libary/index.html.twig', $data);
    }

    #[Route('/libary/form', name: 'libary_form')]
    public function showForm(): Response
    {
        return $this->render('libary/form.html.twig');
    }

    #[Route('/libary/add', name: 'libary_add', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = new Books();

        $book->setTitle((string) $request->request->get('title'));
        $book->setAuthor((string) $request->request->get('author'));
        $book->setIsbn((string) $request->request->get('isbn'));
        $book->setImg((string) $request->request->get('img'));

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('libary');
    }

    #[Route('/libary/show/{id}', name: 'libary_show_one')]
    public function showOneBook(
        BooksRepository $BooksRepository,
        int $id,
    ): Response {
        $book = $BooksRepository
            ->find($id);

        $data = [
            'book' => $book,
        ];

        return $this->render('libary/book.html.twig', $data);
    }

    #[Route('/libary/delete/{id}', name: 'libary_delete_one', methods: ['POST'])]
    public function deleteOneBook(
        ManagerRegistry $doctrine,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('libary');
    }

    #[Route('/libary/updateForm/{id}', name: 'libary_update_one_form')]
    public function showUpdateForm(
        BooksRepository $BooksRepository,
        int $id,
    ): Response {
        $book = $BooksRepository
            ->find($id);

        $data = [
            'book' => $book,
        ];

        return $this->render('libary/update.html.twig', $data);
    }

    #[Route('/libary/update/{id}', name: 'libary_update_one', methods: ['POST'])]
    public function updateOneBook(
        ManagerRegistry $doctrine,
        Request $request,
        int $id,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);
        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }
        $book->setTitle((string) $request->request->get('title'));
        $book->setAuthor((string) $request->request->get('author'));
        $book->setIsbn((string) $request->request->get('isbn'));
        $book->setImg((string) $request->request->get('img'));

        $entityManager->flush();

        return $this->redirectToRoute('libary');
    }
}
