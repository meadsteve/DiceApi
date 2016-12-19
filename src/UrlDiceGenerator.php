<?php

namespace MeadSteve\DiceApi;

use MeadSteve\DiceApi\Dice\Factories\DiceFactory;
use MeadSteve\DiceApi\Dice\Factories\DiceFactoryCollection;
use MeadSteve\DiceApi\Dice\Factories\NumericDiceFactory;
use MeadSteve\DiceApi\Dice\Factories\SpecialDiceFactory;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;

class UrlDiceGenerator
{
    /**
     * @var DiceFactory
     */
    private $diceFactory;

    public function __construct(DiceFactory $diceFactory)
    {
        $this->diceFactory = $diceFactory;
    }

    public function diceFromUrlString($urlString)
    {
        $parts = explode("/", $urlString);
        $parts = array_filter($parts, [$this, 'notBlank']);
        $diceSets = array_map([$this, 'getDiceForPart'], $parts);
        return $this->flattenDiceSets($diceSets);
    }

    /**
     * @param string $part
     *
     * @throws UncreatableDiceException
     *
     * @return Dice[]
     */
    private function getDiceForPart($part)
    {
        $data = $this->parseDiceString($part);
        return $this->diceFactory->buildDice($data["type"], $data["count"]);
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
            throw new UncreatableDiceException("Problem creating dice from incorrectly formated data: " . $part);
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
}
