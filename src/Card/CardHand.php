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
        $valueMap = [
            'KN' => 10,
            'D'  => 10,
            'K'  => 10,
            'A'  => 11
        ];

        $result = 0;

        foreach ($this->hand as $card) {
            $result += $valueMap[$card->rank] ?? (int)$card->rank;
        }

        return $result;
    }

}
