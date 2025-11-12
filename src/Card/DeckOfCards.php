<?php

namespace App\Card;

class DeckOfCards
{
    /** @var Card[] */
    public array $cards = [];

    public function __construct()
    {
        $suits = ['♠', '♣', '♢', '♡'];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'KN', 'D', 'K'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards[] = new Card($rank, $suit);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * @return Card[]
     */
    public function display(int $numOfCards): array
    {
        if ($numOfCards <= 0) {
            return [];
        }

        return array_slice($this->cards, 0, $numOfCards);
    }

    public function drawCard(): ?Card
    {
        return array_shift($this->cards);
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
