<?php
namespace MeadSteve\DiceApi;

interface Dice extends \JsonSerializable
{
    public function size();

    public function jsonSerialize();

    public function roll();
}