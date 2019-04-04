<?php
namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;

class FateDice implements Dice
{

    const VALUES = ["+", "-", " "];

    public function name(): string
    {
        return "dFATE";
    }

    public function roll(): string
    {
        return self::VALUES[array_rand(self::VALUES)];
    }
}
