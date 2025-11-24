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

class ApiDeckDrawControllerJson extends AbstractController
{
    #[Route('/api/deck/draw', name: 'api_deck_draw')]
    public function jsonDeckDraw(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        $data = [
            'card' => $deck->drawCard(),
            'length' => count($deck->getCards()),
        ];

        // return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: 'api_deck_draw_many')]
    public function jsonDeckDrawMany(
        int $num,
        SessionInterface $session,
    ): Response {
        if ($num > 52) {
            throw new \Exception("Can't draw more than 52 cards!");
        }
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        $cardsDrawn = [];
        for ($i = 1; $i <= $num; ++$i) {
            $cardsDrawn[] = $deck->drawCard();
            $session->set('deck', $deck);
        }

        $session->set('deck', $deck);
        $lastCard = end($cardsDrawn);
        $session->set('last_card', $lastCard);
        $cardsLeft = count($deck->getCards());

        $data = [
            'cards' => $cardsDrawn,
            'length' => count($cardsDrawn),
            'cards_left' => $cardsLeft,
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

}
