<?php

namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice;

class SpecialDiceFactory implements DiceFactory
{
    /**
     * @var callable[]
     */
    private $diceTypeMappings;

    public function __construct()
    {
        $this->diceTypeMappings = [
            'steve' => function () {
                return new Dice\SteveDice();
            },
            'fate'  => function () {
                return new Dice\FateDice();
            },
        ];
    }


    public function handlesType(string $type) : bool
    {
        return array_key_exists(
            $this->normaliseType($type),
            $this->diceTypeMappings
        );
    }

    /**
     * @param string $type
     * @param int $number
     * @return Dice[]
     */
    public function buildDice(string $type, int $number) : array
    {
        $diceConstructor = $this->diceTypeMappings[$this->normaliseType($type)];
        return $this->buildNDice($number, $diceConstructor);
    }

    private function normaliseType(string $type): string
    {
        return strtolower($type);
    }

    private function buildNDice($diceCount, callable $constructorFunction)
    {
        $newDice = [];
        for ($i = 0; $i < $diceCount; $i++) {
            $newDice[] = $constructorFunction();
        }
        return $newDice;
    }
}
