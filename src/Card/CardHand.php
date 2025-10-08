<?php

namespace App\Card;

class CardHand
{
    /** @var Card[] */
    private array $hand = [];

    public function drawCard(DeckOfCards $deck): void
    {
        $card = $deck->drawCard();
        if ($card !== null) {
            $this->hand[] = $card;
        }
    }
    /** @return Card[] */
    public function showHand(): array
    {
        return $this->hand;
    }

    public function getLastDrawnCard(): ?Card
    {
        if (!empty($this->hand)) {
            return end($this->hand);
        }
        return null;
    }

    public function getHandValue(): int
    {
        $cardsValue = [
            'KN' => 10,
            'D'  => 10,
            'K'  => 10,
            'A'  => 11
        ];

        $result = 0;

        foreach ($this->hand as $card) {
            $result += $cardsValue[$card->rank] ?? (int)$card->rank;
        }

        return $result;
    }

}
