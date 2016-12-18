<?php
namespace MeadSteve\DiceApi;

interface Dice
{
    public function name() : string;

    public function roll();
}
