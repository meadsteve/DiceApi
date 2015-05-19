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
        return mt_rand(1, $this->size);
    }
}
