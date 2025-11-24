<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'card_start')]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route('card/deck', name: 'card_deck')]
    public function cardDisplay(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        $cardsLeft = count($deck->getCards());

        $data = [
            'hand' => $deck->display($cardsLeft),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route('card/deck/shuffle', name: 'card_shuffle')]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cardsLeft = count($deck->getCards());

        // Clear the session explicitly (clear() returns void)
        $session->clear();

        $data = [
            'hand' => $deck->display($cardsLeft),
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }
}
