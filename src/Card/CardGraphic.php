<?php

namespace App\Card;

class CardGraphic extends Card
{
    private $representation = '🂠';
    public function __construct($suit = null, $rank = null)
    {
        parent::__construct($suit, $rank);
    }

    public function display(): string
    {
        return $this->representation;
    }
}
