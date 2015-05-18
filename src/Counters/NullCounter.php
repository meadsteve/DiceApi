<?php
namespace MeadSteve\DiceApi\Counters;
use MeadSteve\DiceApi\Dice;

class NullCounter implements DiceCounter
{

    /**
     * @param Dice[] $diceCollection
     * @return bool
     */
    public function count(array $diceCollection)
    {
        return true;
    }

    /**
     * @return array
     */
    public function getCounts()
    {
        return [];
    }
}
