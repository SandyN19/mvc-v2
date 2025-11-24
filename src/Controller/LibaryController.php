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
    #[Route('/libary/add', name: 'libary_add', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request,
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = new Books();

        $this->formFields($request, $book);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('libary');
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
        $this->formFields($request, $book);

        $entityManager->flush();

        return $this->redirectToRoute('libary');
    }
    private function formFields(Request $request, Books $book): void
    {
        $book->setTitle((string) $request->request->get('title'));
        $book->setAuthor((string) $request->request->get('author'));
        $book->setIsbn((string) $request->request->get('isbn'));
        $book->setImg((string) $request->request->get('img'));
    }
}
