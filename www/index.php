<?php

use Slim\Http\Request;
use Slim\Http\Response;

require __DIR__ . "/../vendor/autoload.php";

$app = new \Slim\App();

$app->get("/", function(Request$request, Response $response) {
    $response->write("index");
    return $response;
});

$app->get("{dice:(?:/[0-9]*d[0-9]+)+/?}", function(Request $request, Response $response, $args) {
    $response->write("dice: " . $args['dice']);
    return $response;
});

$app->run();