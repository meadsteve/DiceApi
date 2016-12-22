<?php

use MeadSteve\DiceApi\Counters\NullCounter;
use MeadSteve\DiceApi\Counters\RedisCounter;
use MeadSteve\DiceApi\Dice\Factories\DiceFactoryCollection;
use MeadSteve\DiceApi\Dice\Factories\NumericDiceFactory;
use MeadSteve\DiceApi\Dice\Factories\SpecialDiceFactory;
use MeadSteve\DiceApi\Renderer\Html;
use MeadSteve\DiceApi\Renderer\Json;
use MeadSteve\DiceApi\UrlDiceGenerator;
use MeadSteve\DiceApi\DiceApp;
use MeadSteve\DiceApi\Renderer\RendererCollection;
use MeadSteve\DiceApi\RequestHandler\DiceRequestHandler;
use Predis\Client;

require __DIR__ . "/../vendor/autoload.php";

$diceGenerator = new UrlDiceGenerator(
    new DiceFactoryCollection([
        new NumericDiceFactory(),
        new SpecialDiceFactory()
    ])
);

$rendererCollection = new RendererCollection([
    new Json(),
    new Html('http://' . $_SERVER['HTTP_HOST'])
]);

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
$diceRequestHandler = new DiceRequestHandler($diceGenerator, $rendererCollection, $diceCounter);
$app = new DiceApp($diceRequestHandler, $diceCounter);
$app->run();
