<?php
namespace MeadSteve\DiceApi;

use MeadSteve\DiceApi\Dice\DiceGenerator;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceApp extends App
{
    private $diceGenerator;

    public function __construct()
    {
        parent::__construct();

        $this->diceGenerator = new DiceGenerator();

        $this->get("/", [$this, 'index']);
        $this->get("{dice:(?:/[0-9]*[dD][0-9]+)+/?}", [$this, 'getDice']);
    }

    public function index(Request $request, Response $response)
    {
        $response->write("index");
        return $response;
    }

    public function getDice(Request $request, Response $response, $args)
    {
        $diceResponse = $response->withHeader("cache-control", "no-cache")
            ->withHeader("Content-Type", "application/json");
        $dice = $this->diceGenerator->diceFromUrlString($args['dice']);
        $data = [
            "success" => true,
            "dice" => $dice
        ];
        $diceResponse->write(json_encode($data));
        return $diceResponse;
    }
}
