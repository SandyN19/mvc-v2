<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjGameController extends AbstractController
{
    #[Route('/proj/play', name: 'project_play', methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {
        $handCount = $session->get('handCount', 1);

        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        if (!$session->has('playerHands')) {
            $hands = [];
            for ($i = 0; $i < $handCount; $i++) {
                $hands[] = new CardHand();
            }
            $session->set('playerHands', $hands);
            $session->set('currentHand', 0);
        }

        if (!$session->has('dealerHand')) {
            $session->set('dealerHand', new CardHand());
        }
        /** @var CardHand[] $playerHands */
        $playerHands = $session->get('playerHands');

        /** @var CardHand $dealerHand */
        $dealerHand = $session->get('dealerHand');

        foreach ($playerHands as $hand) {
            if (count($hand->showHand()) === 0) {
                $hand->drawCard($deck);
                $hand->drawCard($deck);
            }
        }

        if (count($dealerHand->showHand()) === 0) {
            $dealerHand->drawCard($deck);
            $dealerHand->drawCard($deck);
        }
        $session->set('playerHands', $playerHands);
        $session->set('dealerHand', $dealerHand);
        $session->set('deck', $deck);

        return $this->render('proj/play.html.twig', [
            'playerHands' => $playerHands,
            'dealerHand' => $dealerHand->showHand(),
            'dealerHandValue' => $dealerHand->getHandValue(),
            'username' => $session->get('username'),
            'currentHand' => $session->get('currentHand'),
            'bank' => $session->get('bank'),
            'bet' => $session->get('bet'),
            'result' => $session->get('result'),
            'gameOver' => $session->get('gameOver'),
        ]);
    }

    #[Route('/proj/restart', name: 'project_restart', methods: ['POST'])]
    public function restart(SessionInterface $session): Response
    {
        /** @var int $bet */
        $bet = $session->get('bet');
        /** @var int $handCount */
        $handCount = $session->get('handCount');
        /** @var int $bank */
        $bank = $session->get('bank');

        $bank -= $bet * $handCount;
        $session->set('bank', $bank);

        $session->remove('deck');
        $session->remove('playerHands');
        $session->remove('dealerHand');
        $session->remove('result');
        $session->remove('gameOver');
        $session->remove('currentHand');
        return $this->redirectToRoute('project_play');
    }
}
