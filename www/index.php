<?php

use MeadSteve\DiceApi\Counters\NullCounter;
use MeadSteve\DiceApi\Counters\RedisCounter;
use MeadSteve\DiceApi\UrlDiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use MeadSteve\DiceApi\Renderer\RendererFactory;
use MeadSteve\DiceApi\RequestHandler\DiceRequestHandler;
use Predis\Client;

require __DIR__ . "/../vendor/autoload.php";

$diceGenerator = new UrlDiceGenerator();

$rendererFactory = new RendererFactory('http://' . $_SERVER['HTTP_HOST']);

if (isset($_ENV['REDIS_URL'])) {
    $redis = new Client(
        [
            'host' => parse_url($_ENV['REDIS_URL'], PHP_URL_HOST),
            'port' => parse_url($_ENV['REDIS_URL'], PHP_URL_PORT),
            'password' => parse_url($_ENV['REDIS_URL'], PHP_URL_PASS),
        ]
    );
    $diceCounter = new RedisCounter($redis);
} else {
    $diceCounter = new NullCounter();
}
$diceRequestHandler = new DiceRequestHandler($diceGenerator, $rendererFactory, $diceCounter);
$app = new DiceApp($diceRequestHandler, $diceCounter);
$app->run();
