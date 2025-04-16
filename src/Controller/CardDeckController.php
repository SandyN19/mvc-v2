<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Dice\Card;
use App\Dice\CardHand;
use App\Dice\DeckOfCards;

class CardDeckController extends AbstractController
{
    #[Route("/card/session", name: "card_session")]
    public function home(): Response
    {
        return $this->render('card/session.html.twig');
    }

}