<?php

namespace App\Card;

class CardGraphic extends Card
{
    private $representation = '🂠';
    public function __construct($suit = null, $rank = null)
    {
        parent::construct($rank, $suit);
    }

    public function display(): string
    {
        return $this->representation;
    }
}
