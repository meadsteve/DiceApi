<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;

class DiceGenerator
{
    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
        $parts = array_filter($parts, [$this, 'notBlank']);
        $diceSets = array_map([$this, 'getDiceForPart'], $parts);
        return $this->flattenDiceSets($diceSets);
    }

    /**
     * @param string $part
     * @return Dice[]
     */
    private function getDiceForPart($part)
    {
        $data = $this->parseDiceString($part);
        $type = $data["type"];
        $diceCount = $data["count"];

        if (is_numeric($type)) {
            return $this->buildBasicNumericDice($type, $diceCount);
        }

        switch (strtolower($type)) {
            case "steve":
                return $this->buildSteveDice($type, $diceCount);
        }

        throw new UncreatableDiceException("No idea how to make a d{$type}");
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
        $valid = preg_match("/(?P<count>[0-9]+)?d(?P<type>[^\/]+)/i", $part, $data);
        if (!$valid) {
            throw new UncreatableDiceException("Problem creating dice from incorrectly formated data: " + $part);
        }
        if (!$data["count"]) {
            $data["count"] = 1;
        }
        return $data;
    }

    private function notBlank($string)
    {
        return $string !== "";
    }

    private function buildBasicNumericDice($size, $diceCount) : array
    {
        $newDice = [];
        if ((strlen($size) > 4) || ($size > 9000)) {
            throw new UncreatableDiceException("Only dice with a power level less than 9000 can be created.");
        }
        for ($i = 0; $i < $diceCount; $i++) {
            $newDice[] = $this->newDiceOfSize($size);
        }
        return $newDice;
    }

    private function buildSteveDice($_type, $diceCount)
    {
        $newDice = [];
        for ($i = 0; $i < $diceCount; $i++) {
            $newDice[] = new SteveDice();
        }
        return $newDice;
    }
}
