<?php

namespace MeadSteve\DiceApi\Dice\Factories;

use MeadSteve\DiceApi\Dice;

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
                    $newDice[] = new Dice\SteveDice();
                }
                return $newDice;
            },
            'fate' => function ($_type, $diceCount) {
                $newDice = [];
                for ($i = 0; $i < $diceCount; $i++) {
                    $newDice[] = new Dice\FateDice();
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
