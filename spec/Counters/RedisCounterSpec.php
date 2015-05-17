<?php

namespace spec\MeadSteve\DiceApi\Counters;

use MeadSteve\DiceApi\Dice;
use PhpSpec\ObjectBehavior;
use Predis\Connection\ConnectionException;
use Prophecy\Argument;

require_once __DIR__ . "/RedisClientMock.php";

class RedisCounterSpec extends ObjectBehavior
{
    function let(RedisClient $redisClient)
    {
        $this->beConstructedWith($redisClient);
        $redisClient->incr(Argument::any())->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MeadSteve\DiceApi\Counters\RedisCounter');
    }

    function it_counts_single_dice_as_the_correct_type(Dice $dice, RedisClient $redisClient)
    {
        $dice->size()->willReturn(6);
        $this->count([$dice]);
        $redisClient->incr("dice-count-d6")->shouldHaveBeenCalledTimes(1);
    }

    function it_counts_multiple_dice_of_a_single_type(Dice $dice, RedisClient $redisClient)
    {
        $dice->size()->willReturn(6);
        $this->count([$dice, $dice]);
        $redisClient->incr("dice-count-d6")->shouldHaveBeenCalledTimes(2);
    }

    function it_counts_multiple_dice_of_multiple_types(Dice $d6, Dice $d4, RedisClient $redisClient)
    {
        $d6->size()->willReturn(6);
        $d4->size()->willReturn(4);
        $this->count([$d6, $d4, $d6]);
        $redisClient->incr("dice-count-d6")->shouldHaveBeenCalledTimes(2);
        $redisClient->incr("dice-count-d4")->shouldHaveBeenCalledTimes(1);
    }

    function it_ignores_connection_errors(Dice $dice, RedisClient $redisClient)
    {

        $redisClient->incr(Argument::any())->willThrow(ConnectionException::class);
        $this->count([$dice])->shouldReturn(false);
    }
}
