<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards
{
    public $cards = array();

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

    public function display($numOfCards): array
    {
        for ($i = 0; $i <= $numOfCards; $i++) {
            return $this->cards;
        }
    }
    public function drawCard()
    {
        return array_shift($this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}
