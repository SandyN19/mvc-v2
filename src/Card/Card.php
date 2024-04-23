<?php

namespace App\Card;

class Card
{
    public $suit;
    public $rank;

    public function __construct($suit, $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }
    public function getAsString(): string
    {
        return "[{$this->rank}{$this->suit}]";
    }
}
