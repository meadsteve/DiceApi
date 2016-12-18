<?php
namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice;

interface DiceFactory
{
    public function handlesType(string $type): bool;

    /**
     * @param string $type
     * @param int $number
     * @return Dice[]
     */
    public function buildDice(string $type, int $number): array;
}