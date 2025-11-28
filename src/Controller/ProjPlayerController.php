<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjPlayerController extends AbstractController
{
    #[Route('/proj/hit/{index}', name: 'project_hit', methods: ['POST'])]
    public function hit(SessionInterface $session, int $index): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var CardHand[] $hands */
        $hands = $session->get('playerHands');

        if (isset($hands[$index])) {
            $hands[$index]->drawCard($deck);
            if ($hands[$index]->getHandValue() >= 21) {
                $current = $session->get('currentHand');
                $handCount = count($hands);

                if ($current < $handCount - 1) {
                    $session->set('currentHand', $current + 1);
                } else {
                    return $this->redirectToRoute('project_dealer');
                }
            }
        }

        $session->set('playerHands', $hands);
        $session->set('deck', $deck);

        return $this->redirectToRoute('project_play');
    }

    #[Route('/proj/stand', name: 'project_stand', methods: ['POST'])]
    public function stand(SessionInterface $session): Response
    {
        $current = $session->get('currentHand');
        /** @var CardHand[] $hands */
        $hands = $session->get('playerHands');
        $handCount = count($hands);

        if ($current < $handCount - 1) {
            $session->set('currentHand', $current + 1);
            return $this->redirectToRoute('project_play');
        }
        return $this->redirectToRoute('project_dealer');
    }

}
