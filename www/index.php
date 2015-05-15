<?php

use Slim\Http\Request;
use Slim\Http\Response;

require __DIR__ . "/../vendor/autoload.php";

$app = new \Slim\App();

$app->get("/", function(Request$request, Response $response) {
    $response->write("index");
    return $response;
});

$app->get("{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function(Request $request, Response $response, $args) {
    $diceResponse = $response->withHeader("cache-control", "no-cache")
        ->withHeader("Content-Type", "application/json");
    $data = [
        "success" => true,
        "debug" => $args['dice']
    ];
    $diceResponse->write(json_encode($data));
    return $diceResponse;
});

$app->run();