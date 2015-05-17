<?php
namespace MeadSteve\DiceApi\Counters;

use MeadSteve\DiceApi\Dice;

interface DiceCounter
{
    /**
     * @param Dice[] $diceCollection
     * @return bool
     */
    public function count(array $diceCollection);

    /**
     * @return array
     */
    public function getCounts();
}
