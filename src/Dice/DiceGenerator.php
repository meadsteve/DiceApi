<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;

class DiceGenerator
{
    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
        $parts = array_filter($parts, function($part) {return $part !== "";});
        $diceSets = array_map([$this, 'getDiceForPart'], $parts);
        return $this->flattenDiceSets($diceSets);
    }

    /**
     * @param string $part
     * @return Dice[]
     */
    private function getDiceForPart($part)
    {
        $newDice = [];
        $data = $this->parseDiceString($part);
        if ((strlen($data["size"]) > 4) || ($data["size"] > 9000)) {
            throw new UncreatableDiceException("Only dice with a power level less than 9000 can be created.");
        }
        for ($i = 0; $i < $data["count"]; $i++) {
            $newDice[] = $this->newDiceOfSize($data["size"]);
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

    /**
     * @param array[]
     * @return array
     */
    private function flattenDiceSets($diceSets)
    {
        $dice = [];
        foreach ($diceSets as $set) {
            $dice = array_merge($dice, $set);
        }
        return $dice;
    }

    /**
     * @param string $part
     * @return array
     */
    private function parseDiceString($part)
    {
        $data = [];
        $valid = preg_match("/(?P<count>[0-9]+)?d(?P<size>[0-9]+)/i", $part, $data);
        if (!$valid) {
            throw new UncreatableDiceException("Problem creating dice from incorrectly formated data: " + $part);
        }
        if (!$data["count"]) {
            $data["count"] = 1;
        }
        return $data;

    }
}
