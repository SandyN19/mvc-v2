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

class ApiControllerJson
{
    #[Route("/api", name: "api_start")]
    public function jsonStart(): Response
    {
        $routes = [
           '/quote' => 'Get a random quote',
           '/deck' => 'Get a deck of cards',
            '/deck/shuffle' => 'Shuffle deck',
            '/deck/draw' => 'Draw a card from deck',
            '/deck/draw/{num}' => 'Draw multiple cards from deck',
            '/game' => 'Get game status',
        ];

        //return new JsonResponse($data);
        $response = new JsonResponse($routes);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/quote", name: "api_quote")]
    public function jsonQuote(): Response
    {

        $quotes = [
            'Be yourself; everyone else is already taken. - Oscar Wilde',
            'So many books, so little time. - Frank Zappa',
            "Two things are infinite: the universe and human stupidity; and Im not sure about the universe. - Albert Einstein",
            "Im selfish, impatient and a little insecure. I make mistakes, I am out of control and at times hard to handle. But if you cant handle me at my worst, then you sure as hell dont deserve me at my best. - Marilyn Monroe"
        ];

        $data = [
            'Today-date' => date('Y-m-d H:i:s'),
            'random-quote' => $quotes[array_rand($quotes)],
        ];

        //return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck", name: "api_deck")]
    public function jsonDeck(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);

        }
        $deck = $session->get('deck');

        //return new JsonResponse($data);
        $response = new JsonResponse($deck->getCards());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/deck/shuffle", name: "api_deck_shuffle")]
    public function jsonDeckShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);


        //return new JsonResponse($data);
        $response = new JsonResponse($deck->getCards());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/deck/draw", name: "api_deck_draw")]
    public function jsonDeckDraw(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);

        }
        $deck = $session->get('deck');


        $data = [
            "card" => $deck->drawCard(),
            "length" => count($deck->getCards()),
        ];

        //return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/deck/draw/{num<\d+>}", name: "api_deck_draw_many")]
    public function jsonDeckDrawMany(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new \Exception("Can't draw more than 52 cards!");
        }
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');
        $cardsDrawn = [];
        for ($i = 1; $i <= $num; $i++) {
            $cardsDrawn[] = $deck->drawCard();
            $session->set('deck', $deck);
        }

        $session->set('deck', ($deck));
        $lastCard = end($cardsDrawn);
        $session->set('last_card', $lastCard);
        $cardsLeft = count($deck->getCards());


        $data = [
            "cards" => $cardsDrawn,
            "length" => count($cardsDrawn),
            "cards_left" => $cardsLeft,
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/game", name: "api_game")]
    public function gameStatus(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        if (!$session->has('playerHand')) {
            $playerHand = new CardHand();
            $session->set('playerHand', $playerHand);
        }

        if (!$session->has('dealerHand')) {
            $dealerHand = new CardHand();
            $session->set('dealerHand', $dealerHand);
        }

        $deck = $session->get('deck');
        $playerHand = $session->get('playerHand');
        $dealerHand = $session->get('dealerHand');

        $data = [
            'playerHand' => $playerHand->showHand(),
            'dealerHand' => $dealerHand->showHand(),
            'playerValue' => $playerHand->getHandValue(),
            'dealerValue' => $dealerHand->getHandValue(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
