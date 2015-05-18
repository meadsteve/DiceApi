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
        $this->alwaysRoll = $alwaysRoll;
    }
    public function size()
    {
        return $this->dice->size();
    }

    public function roll()
    {
        return $this->alwaysRoll;
    }
}
