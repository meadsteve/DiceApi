<?php

namespace spec\MeadSteve\DiceApi\Counters;

use Predis\Client;

class RedisClient extends Client
{
    public function hincrby($key, $field, $inc)
    {
        return true;
    }

    public function hgetall($key)
    {
        return [];
    }
}
 