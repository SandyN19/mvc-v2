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

}