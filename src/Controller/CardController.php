<?php

namespace App\Controller;
use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardController extends AbstractController
{
    #[Route("/game/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/game/card/deck", name: "card_deck")]
    public function cardRandom(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "hand" => $deck->deal(52),
            //"diceString" => $card->getAsString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }
    
}