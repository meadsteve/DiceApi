<?php
namespace MeadSteve\DiceApi;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceApp extends App
{
    public function __construct()
    {
        parent::__construct();
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
        $data = [
            "success" => true,
            "debug" => $args['dice']
        ];
        $diceResponse->write(json_encode($data));
        return $diceResponse;
    }
}
