<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibaryFormController extends AbstractController
{
    #[Route('/libary', name: 'libary')]
    public function showAllBooks(
        BooksRepository $BooksRepository,
    ): Response {
        return $this->render('libary/index.html.twig', [
        'books' => $BooksRepository->findAll()
    ]);
    }

    #[Route('/libary/show/{id}', name: 'libary_show_one')]
    public function showOneBook(
        BooksRepository $BooksRepository,
        int $id,
    ): Response {
        return $this->render('libary/book.html.twig', [
        'book' => $BooksRepository->find($id)
    ]);
    }


    #[Route('/libary/form', name: 'libary_form')]
    public function showForm(): Response
    {
        return $this->render('libary/form.html.twig');
    }

    #[Route('/libary/updateForm/{id}', name: 'libary_update_one_form')]
    public function showUpdateForm(
        BooksRepository $BooksRepository,
        int $id,
    ): Response {
        return $this->render('libary/update.html.twig', [
        'book' => $BooksRepository->find($id)
    ]);
    }

}
