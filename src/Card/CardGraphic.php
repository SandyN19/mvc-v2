<?php

namespace App\Card;

class CardGraphic extends Card
{
    private string $representation = '🂠';
    public function __construct(?string $suit = null, ?string $rank = null)
    {
        parent::__construct($rank, $suit);
    }

    public function display(): string
    {
        return $this->representation;
    }
}
