<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;

class ZeropointDice implements Dice
{
    public function size()
    {
        return 0;
    }

    public function roll()
    {
        return "Singularity";
    }
}
