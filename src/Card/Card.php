<?php

namespace App\Card;

class Card
{
    public string $rank;
    public string $suit;

    public function __construct(string $rank, string $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    public function getAsString(): string
    {
        return "[{$this->rank}{$this->suit}]";
    }
}
