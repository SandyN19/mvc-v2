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
        if ($session->has('deck')) {
            $deck = $session->get('deck');
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        
        $cardsLeft = count($deck->getCards());


        $data = [
            "hand" => $deck->display($cardsLeft),
            'session' => $session->clear()
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("card/deck/draw", name: "card_draw")]
    public function cardDraw(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($session->has('deck')) {
            $deck = ($session->get('deck'));
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $lastCard = $session->get('last_card');

        $data = [
            "card" => $lastCard,
            "length" => count($deck->getCards()),
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("card/deck/drawACard", name: "draw_a_card", methods: ['POST'])]
    public function drawACard(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = ($session->get('deck'));
        $cardHand = new CardHand();
        $cardHand->drawCard($deck);
        $session->set('deck', ($deck));
        $lastCard = $cardHand->getLastDrawnCard()->getAsString();
        $session->set('last_card', $lastCard);


        return $this->redirectToRoute('card_draw');
    }


    #[Route("card/deck/draw/{num<\d+>}", name: "card_draw_num")]
    public function cardDrawNum(
        int $num,
        Request $request,
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

        return $this->render('card/draw_many.html.twig', $data);

    }
}
