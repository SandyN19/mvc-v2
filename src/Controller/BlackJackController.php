<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BlackJackController extends AbstractController
{
    #[Route('/game/play', name: 'game_play', methods: ['GET'])]
    public function play(
        SessionInterface $session,
    ): Response {
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
            'playerHandValue' => $playerHand->getHandValue(),
            'dealerHandValue' => $dealerHand->getHandValue(),
            'result' => $session->get('result'),
            'gameOver' => $session->get('gameOver'),
        ];

        return $this->render('game/play.html.twig', $data);
    }

    #[Route('/game/hit', name: 'game_hit', methods: ['POST'])]
    public function hit(
        SessionInterface $session,
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var CardHand $playerHand */
        $playerHand = $session->get('playerHand');
        $playerHand->drawCard($deck);
        $session->set('deck', $deck);
        $session->set('playerHand', $playerHand);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stand', name: 'game_stand', methods: ['POST'])]
    public function stand(
        SessionInterface $session,
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var CardHand $dealerHand */
        $dealerHand = $session->get('dealerHand');
        /** @var CardHand $playerHand */
        $playerHand = $session->get('playerHand');

        if ($playerHand->getHandValue() > 21) {
            $dealerHand->drawCard($deck);
            $session->set('result', 'Dealer Wins');
        }
        while ($dealerHand->getHandValue() < 17) {
            $dealerHand->drawCard($deck);
        }
        if ($playerHand->getHandValue() > 21) {
            $session->set('result', 'Player Bust');
        } elseif ($dealerHand->getHandValue() > 21) {
            $session->set('result', 'Dealer Bust');
        } elseif ($playerHand->getHandValue() === $dealerHand->getHandValue()) {
            $session->set('result', 'Push');
        } elseif ($playerHand->getHandValue() < $dealerHand->getHandValue()) {
            $session->set('result', 'Dealer Wins');
        } else {
            $session->set('result', 'Player Wins');
        }
        $session->set('gameOver', true);
        $session->set('deck', $deck);
        $session->set('dealerHand', $dealerHand);

        return $this->redirectToRoute('game_play');
    }
}
