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

class ApiControllerJson extends AbstractController
{
    #[Route('/api/json', name: 'api_start_json')]
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

        // return new JsonResponse($data);
        $response = new JsonResponse($routes);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function jsonQuote(): Response
    {
        $quotes = [
            'Be yourself; everyone else is already taken. - Oscar Wilde',
            'So many books, so little time. - Frank Zappa',
            'Two things are infinite: the universe and human stupidity; and Im not sure about the universe. - Albert Einstein',
            'Im selfish, impatient and a little insecure. I make mistakes, I am out of control and at times hard to handle. But if you cant handle me at my worst, then you sure as hell dont deserve me at my best. - Marilyn Monroe',
        ];

        $data = [
            'Today-date' => date('Y-m-d H:i:s'),
            'random-quote' => $quotes[array_rand($quotes)],
        ];

        // return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/game', name: 'api_game')]
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

        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var CardHand $playerHand */
        $playerHand = $session->get('playerHand');
        /** @var CardHand $dealerHand */
        $dealerHand = $session->get('dealerHand');

        $data = [
            'playerHand' => $playerHand->showHand(),
            'dealerHand' => $dealerHand->showHand(),
            'playerValue' => $playerHand->getHandValue(),
            'dealerValue' => $dealerHand->getHandValue(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return $response;
    }

}
