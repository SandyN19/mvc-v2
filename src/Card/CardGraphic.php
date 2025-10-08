<?php

namespace App\Card;

class CardGraphic extends Card
{
    private string $representation = '🂠';
    public function __construct(string $rank = '', string $suit = '')
    {
        parent::__construct($rank, $suit);
    }

    public function display(): string
    {
        return $this->representation;
    }
}
