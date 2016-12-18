<?php

namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use MeadSteve\DiceApi\Dice\ZeropointDice;

class NumericDiceFactory implements DiceFactory
{
    public function handlesType(string $type) : bool
    {
        return is_numeric($type);
    }

    /**
     * @param string $type
     * @param int $number
     * @return Dice[]
     */
    public function buildDice(string $type, int $number) : array
    {
        $newDice = [];
        if ((strlen($type) > 4) || ($type > 9000)) {
            throw new UncreatableDiceException("Only dice with a power level less than 9000 can be created.");
        }
        for ($i = 0; $i < $number; $i++) {
            $newDice[] = $this->newDiceOfSize($type);
        }
        return $newDice;
    }

    /**
     * @param $size
     * @return Dice
     */
    private function newDiceOfSize($size)
    {
        if ($size == 0) {
            return new ZeropointDice();
        }
        return new BasicDice($size);
    }
}
