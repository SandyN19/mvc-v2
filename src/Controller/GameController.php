<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_start')]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/game/init', name: 'game_init_get', methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('game/init.html.twig');
    }

    #[Route('/game/init', name: 'game_init_post', methods: ['POST'])]
    public function initCallback(
        SessionInterface $session,
    ): Response {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);
        $playerHand = new CardHand();
        $session->set('playerHand', $playerHand);
        $dealerHand = new CardHand();
        $session->set('dealerHand', $dealerHand);
        $session->set('result', null);
        $session->set('gameOver', false);

        $playerHand->drawCard($deck);
        $playerHand->drawCard($deck);
        $dealerHand->drawCard($deck);

        return $this->redirectToRoute('game_play');
    }
}
