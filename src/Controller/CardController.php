<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("card/deck", name: "card_deck")]
    public function cardDisplay(SessionInterface $session): Response
    {
        if ($session->has('deck')) {
            $deck = $session->get('deck');
        } else {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $cardsLeft = count($deck->getCards());

        $data = [
            "hand" => $deck->display($cardsLeft),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("card/deck/shuffle", name: "card_shuffle")]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);


        $cardsLeft = count($deck->getCards());

        $data = [
            "hand" => $deck->display($cardsLeft),
            'session' => $session->clear()
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }
    #[Route("card/deck/draw", name: "card_draw")]
    public function cardDraw(SessionInterface $session): Response
    {
        if ($session->has('deck')) {
            $deck = $session->get('deck');
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        $cardsLeft = count($deck->getCards());


        $data = [
            "card" => $deck->drawCard(),
            "length" => count($deck->getCards()),

        ];

        return $this->render('card/draw.html.twig', $data);
    }
    #[Route("card/deck/draw/{num<\d+>}", name: "card_draw_many")]
    public function cardDrawMany(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new \Exception("Can't draw more than 52 cards!");
        }
        if ($session->has('deck')) {
            $deck = $session->get('deck');
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
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

        return $this->render('card/draw_many.html.twig', $data);
    }


}
