<?php
namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;

class SteveDice implements Dice
{

    public function name(): string
    {
        return "dSTEVE";
    }

    public function roll(): string
    {
        return "Steve";
    }
}
