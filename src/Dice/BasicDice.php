<?php


namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\Dice;

class BasicDice implements Dice
{
    /**
     * @var int
     */
    private $size;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

    public function name() : string
    {
        return "d{$this->size}";
    }

    public function roll(): int
    {
        if (! function_exists('random_int')) {
            return mt_rand(1, $this->size);
        }

        return random_int(1, $this->size);
    }
}
