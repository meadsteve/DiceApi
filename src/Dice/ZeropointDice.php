<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;

class ZeropointDice implements Dice
{
    public function name()
    {
        return "d0";
    }

    public function roll()
    {
        return "Singularity";
    }
}
