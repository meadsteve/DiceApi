<?php

namespace MeadSteve\DiceApi\DiceDecorators;

use MeadSteve\DiceApi\Dice;

class TotallyLegit implements Dice
{
    /**
     * @var Dice
     */
    private $dice;

    /**
     * @var int
     */
    private $alwaysRoll;

    public function __construct(Dice $dice, int $alwaysRoll = 6)
    {
        $this->dice = $dice;
        $this->alwaysRoll = (int) $alwaysRoll;
    }
    public function name() : string
    {
        return $this->dice->name();
    }

    public function roll(): int
    {
        return $this->alwaysRoll;
    }
}
