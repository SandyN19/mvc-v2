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
        $book = $BooksRepository
            ->find($id);

        $data = [
            'book' => $book,
        ];

        return $this->render('libary/update.html.twig', $data);
    }

}
