<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiLibaryControllerJson extends AbstractController
{
    #[Route('/api/libary/books', name: 'api_libary_books')]
    public function books(BooksRepository $BooksRepository): Response
    {
        $books = $BooksRepository
            ->findAll();

        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'img' => $book->getImg(),
            ];
        }

        $response = new JsonResponse(['books' => $data]);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/libary/book/{isbn}', name: 'api_libary_books_isbn')]
    public function booksByIsbn(BooksRepository $BooksRepository, string $isbn): Response
    {
        $book = $BooksRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return new JsonResponse([
                'error' => "No book found with isbn: $isbn",
            ], 404);
        }

        $data = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn' => $book->getIsbn(),
            'img' => $book->getImg(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
