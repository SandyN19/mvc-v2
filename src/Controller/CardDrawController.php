<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardDrawController extends AbstractController
{
    #[Route('card/deck/draw', name: 'card_draw')]
    public function cardDraw(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        $data = [
            'card' => $deck->drawCard(),
            'length' => count($deck->getCards()),
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("card/deck/draw/{num<\d+>}", name: 'card_draw_many')]
    public function cardDrawMany(
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

        return $this->render('card/draw_many.html.twig', $data);
    }
}
