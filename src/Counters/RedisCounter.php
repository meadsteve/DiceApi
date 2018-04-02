<?php

namespace MeadSteve\DiceApi\Counters;

use MeadSteve\DiceApi\Dice;
use Predis\Client;
use Predis\Connection\ConnectionException;

class RedisCounter implements DiceCounter
{

    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param Dice[] $diceCollection
     * @return bool
     */
    public function count(array $diceCollection)
    {
        try {
            foreach ($diceCollection as $dice) {
                $this->redisClient->hincrby("dice-count", $dice->name(), 1);
            }
        } catch (ConnectionException $connectError) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getCounts()
    {
        return $this->redisClient->hgetall("dice-count");
    }
}
