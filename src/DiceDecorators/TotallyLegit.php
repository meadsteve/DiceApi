<?php

namespace MeadSteve\DiceApi\DiceDecorators;

use MeadSteve\DiceApi\Dice;

class TotallyLegit implements Dice
{
    private $dice;
    private $alwaysRoll;

    public function __construct(Dice $dice, $alwaysRoll = 6)
    {
        $this->dice = $dice;
        $this->alwaysRoll = (int) $alwaysRoll;
    }
    public function name()
    {
        return $this->dice->name();
    }

    public function roll()
    {
        return $this->alwaysRoll;
    }
}
