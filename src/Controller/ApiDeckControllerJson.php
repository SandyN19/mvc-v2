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

class ApiDeckControllerJson extends AbstractController
{
    #[Route('/api/deck', name: 'api_deck')]
    public function jsonDeck(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        // return new JsonResponse($data);
        $response = new JsonResponse($deck->getCards());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_shuffle')]
    public function jsonDeckShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        // return new JsonResponse($data);
        $response = new JsonResponse($deck->getCards());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

}
