<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjDealerController extends AbstractController
{
    #[Route('/proj/dealer', name: 'project_dealer', methods: ['GET'])]
    public function dealer(SessionInterface $session): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var CardHand $dealerHand */
        $dealerHand = $session->get('dealerHand');
        /** @var CardHand[] $hands */
        $hands = $session->get('playerHands');
        /** @var int $bet */
        $bet = $session->get('bet');
        /** @var int $bank */
        $bank = $session->get('bank');

        while ($dealerHand->getHandValue() < 17) {
            $dealerHand->drawCard($deck);
        }

        $results = [];

        foreach ($hands as $index => $hand) {
            $playerValue = $hand->getHandValue();
            $dealerValue = $dealerHand->getHandValue();
            $handLabel = "Hand " . ($index + 1);

            if ($playerValue === 21 && count($hand->showHand()) === 2) {
                $win = $bet * 2.5;
                $bank += $win;
                $results[] = "$handLabel: Blackjack! +$win kr";
                continue;
            }

            if ($playerValue > 21) {
                $bank -= $bet;
                $results[] = "$handLabel: Bust -$bet kr";
                continue;
            }

            if ($dealerValue > 21) {
                $win = $bet * 2;
                $bank += $win;
                $results[] = "$handLabel: Dealer bust +$win kr";
                continue;
            }

            if ($playerValue === $dealerValue) {
                $results[] = "$handLabel: Push (0 kr)";
                continue;
            }

            if ($playerValue > $dealerValue) {
                $win = $bet * 2;
                $bank += $win;
                $results[] = "$handLabel: Win +$win kr";
                continue;
            }

            $bank -= $bet;
            $results[] = "$handLabel: Loss -$bet kr";
        }

        $session->set('bank', $bank);
        $session->set('result', $results);
        $session->set('dealerHand', $dealerHand);
        $session->set('gameOver', true);

        return $this->redirectToRoute('project_play');
    }
}
