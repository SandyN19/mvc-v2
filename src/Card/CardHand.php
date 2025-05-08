<?php

namespace App\Card;

class CardHand
{
    private $hand = [];

    public function drawCard(DeckOfCards $deck)
    {
        $this->hand[] = $deck->drawCard();
    }

    public function showHand(): array
    {
        return $this->hand;
    }

    public function getLastDrawnCard()
    {
        if (!empty($this->hand)) {
            return end($this->hand);
        }
        return null;
    }

    public function getHandValue(): int
    {
        $result = 0;
        foreach ($this->hand as $card) {
            if ($card->rank === 'A') {
                $result += 11;
            } elseif (in_array($card->rank, ['KN', 'D', 'K'])) {
                $result += 10;
            } else {
                $result += (int)$card->rank;
            }
        }
        return $result;
    }


}
