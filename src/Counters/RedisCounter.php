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
                $this->redisClient->incr('dice-count-d' . $dice->size());
            }
        } catch (ConnectionException $connectError) {
            return false;
        }
        return true;
    }
}
