<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardControllerJson
{
    #[Route("/api/deck", name: "api/deck")]
    public function apiDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "hand" => $deck->display(52),
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api/deck/shuffle")]
    public function apiDecShuffle(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $data = [
            "hand" => $deck->display(52),
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api/deck/draw")]
    public function apiDraw(
        SessionInterface $session
    ): Response {
        if ($session->has('deck')) {
            $deck = ($session->get('deck'));
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $cardHand = new CardHand();
        $cardHand->drawCard($deck);


        $data = [
            "card" => $cardHand->showHand(),
            "length" => count($deck->getCards()),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api/deck/draw/{num<\d+>}")]
    public function apiDrawNum(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new \Exception("Can not draw more than 52 cards!");

        }
        if ($session->has('deck')) {
            $deck = ($session->get('deck'));
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', ($deck));
        }

        $cardHand = new CardHand();
        $cardsDrawn = [];


        for ($i = 1; $i <= $num; $i++) {
            $cardHand->drawCard($deck);
        }

        foreach ($cardHand->showHand() as $card) {
            $cardsDrawn[] = $card->getAsString();
        }

        $session->set('deck', ($deck));
        $lastCard = end($cardsDrawn);
        $session->set('last_card', $lastCard);

        $data = [
            "card" => $cardsDrawn,
            "length" => count($deck->getCards()),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
