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
    public function apiDeck(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        $cardsLeft = count($deck->getCards());

        $data = [
            "hand" => $deck->display($cardsLeft),
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api/deck/shuffle")]
    public function apiDecShuffle(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        $deck->shuffle();

        $cardsLeft = count($deck->getCards());

        $data = [
            "hand" => $deck->display($cardsLeft),
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
        $deck = ($session->get('deck'));

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

        $deck = ($session->get('deck'));

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
