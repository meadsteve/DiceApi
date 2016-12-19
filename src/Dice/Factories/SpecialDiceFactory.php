<?php

namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice\BasicDice;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\Dice\SteveDice;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use MeadSteve\DiceApi\Dice\ZeropointDice;

class SpecialDiceFactory implements DiceFactory
{
    /**
     * @var callable[]
     */
    private $diceTypeCallbacks;

    public function __construct()
    {
        $this->diceTypeCallbacks = [
            'steve' => function ($_type, $diceCount) {
                $newDice = [];
                for ($i = 0; $i < $diceCount; $i++) {
                    $newDice[] = new SteveDice();
                }
                return $newDice;
            }
        ];
    }


    public function handlesType(string $type) : bool
    {
        return array_key_exists(
            $this->normaliseType($type),
            $this->diceTypeCallbacks
        );
    }

    /**
     * @param string $type
     * @param int $number
     * @return Dice[]
     */
    public function buildDice(string $type, int $number) : array
    {
        $function = $this->diceTypeCallbacks[$this->normaliseType($type)];
        return $function($type, $number);
    }

    private function normaliseType(string $type): string
    {
        return strtolower($type);
    }
}
