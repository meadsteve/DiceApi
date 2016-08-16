<?php


namespace MeadSteve\DiceApi;

class BasicDice implements Dice
{

    private $size;

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function size()
    {
        return $this->size;
    }

    public function roll()
    {
        if (! function_exists('random_int')) {
            return mt_rand(1, $this->size);
        }

        return random_int(1, $this->size);
    }
}
