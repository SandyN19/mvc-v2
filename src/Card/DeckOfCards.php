<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards {
    public $cards = array();

    public function __construct() {
        $suits = array("♠", '♣', '♦', '♥');
        $ranks = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'KN', 'D', 'K');

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $this->cards[] = new Card($suit, $rank);
            }
        }
    }

    public function shuffle(): void {
        shuffle($this->cards);
    }

    public function deal($numCards): array {
        return array_splice($this->cards, 0, $numCards);
    }
}