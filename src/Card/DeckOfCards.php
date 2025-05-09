<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    /** @var Card[] */
    public array $cards = array();

    public function __construct()
    {
        $suits = array("♠", '♣', '♢', '♡');
        $ranks = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'KN', 'D', 'K');

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
     * @param int $numOfCards
     * @return Card[]
     */
    public function display( int $numOfCards): array
    {
        for ($i = 0; $i <= $numOfCards; $i++) {
            return $this->cards;
        }
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
