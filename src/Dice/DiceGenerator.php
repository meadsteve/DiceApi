<?php

namespace MeadSteve\DiceApi\Dice;

use MeadSteve\DiceApi\BasicDice;
use MeadSteve\DiceApi\Dice;


class DiceGenerator
{
    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
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
        $data = [];
        $valid = preg_match("/(?P<count>[0-9]+)?d(?P<size>[0-9]+)/i", $part, $data);
        if ($valid) {
            if ((strlen($data["size"]) > 4) || ($data["size"] > 9000)) {
                throw new UncreatableDiceException("Only dice with a power level less than 9000 can be created.");
            }
            if (!$data["count"]) {
                $data["count"] = 1;
            }
            for ($i = 0; $i < $data["count"]; $i++) {
                $newDice[] = $this->newDiceOfSize($data["size"]);
            }
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
}
