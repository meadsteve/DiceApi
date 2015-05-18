<?php
namespace MeadSteve\DiceApi;

use League\CommonMark\CommonMarkConverter;
use MeadSteve\DiceApi\Counters\DiceCounter;
use MeadSteve\DiceApi\Dice\DiceGenerator;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use MeadSteve\DiceApi\DiceDecorators\TotallyLegit;
use MeadSteve\DiceApi\Renderer\RendererFactory;
use MeadSteve\DiceApi\Renderer\UnknownRendererException;
use MeadSteve\DiceApi\Renderer\UnrenderableDiceException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceApp extends App
{
    private $diceGenerator;
    private $diceCounter;

    public function __construct(DiceGenerator $diceGenerator, DiceCounter $diceCounter)
    {
        parent::__construct();

        $this->diceGenerator = $diceGenerator;
        $this->diceCounter = $diceCounter;

        $this->get("/", [$this, 'index']);
        $this->get("/dice-stats", [$this, 'diceStats']);
        $this->get("{dice:(?:/[0-9]*[dD][0-9]+)+/?}", [$this, 'getDice']);

        $this->get("/html{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function (Request $request, $response, $args) {
            return $this->getDice($request->withHeader('accept', 'text/html'), $response, $args);
        });
        $this->get("/json{dice:(?:/[0-9]*[dD][0-9]+)+/?}", function (Request $request, $response, $args) {
            return $this->getDice($request->withHeader('accept', 'application/json'), $response, $args);
        });
    }

    public function index(Request $request, Response $response)
    {
        $indexFilePath = __DIR__ . "/generated-index.html";
        if (!file_exists($indexFilePath)) {
            $converter = new CommonMarkConverter();
            $indexBody = $converter->convertToHtml(file_get_contents(__DIR__ . "/../README.md"));
            $indexContent = file_get_contents(__DIR__ . "/../www/templates/index.html");
            $indexContent = str_replace("{{body}}", $indexBody, $indexContent);
            file_put_contents($indexFilePath, $indexContent);
            $response->write($indexContent);
        } else {
            $indexContent = file_get_contents($indexFilePath);
            $response->write($indexContent);
        }
        return $response;
    }

    public function diceStats(Request $request, Response $response)
    {
        $countData = $this->diceCounter->getCounts();
        return $response->write(json_encode($countData))
            ->withHeader("Content-Type", "application/json");
    }

    public function getDice(Request $request, Response $response, $args)
    {
        $diceResponse = $response->withHeader("cache-control", "no-cache");
        try {
            $dice = $this->diceGenerator->diceFromUrlString($args['dice']);
            if ($request->hasHeader('totally-legit')) {
                $dice = $this->makeDiceTotallyLegit($dice, $request);
            }
            $diceResponse = $this->writeAppropriateFormatResponse($request, $diceResponse, $dice);
            $this->diceCounter->count($dice);
        } catch (UncreatableDiceException $creationError) {
            $diceResponse = $diceResponse->withStatus(400)
                ->write("Unable to roll dice: " . $creationError->getMessage());
        } catch (UnrenderableDiceException $renderError) {
            $diceResponse = $diceResponse->withStatus(400)
                ->write("Unable to render request: " . $renderError->getMessage());
        }
        return $diceResponse;
    }

    private function writeAppropriateFormatResponse(Request $request, Response $response, $dice)
    {
        $acceptHeader = $request->getHeader('accept');
        $requestedContentType = $acceptHeader[0];
        try {
            $rendererFactory = new RendererFactory();
            $renderer = $rendererFactory->newForAcceptType($requestedContentType);
            $responseWithOutput = $response->write($renderer->renderDice($dice))
                ->withHeader("Content-Type", $renderer->contentType());
        } catch (UnknownRendererException $error) {
            $responseWithOutput = $response->withStatus(406);
            $responseWithOutput->write("Not sure how to respond with: " . $requestedContentType);
        }
        return $responseWithOutput;
    }

    private function makeDiceTotallyLegit($dice, Request $request)
    {
        $rolledValue = $request->getHeader('totally-legit');
        return array_map(
            function (Dice $dice) use ($rolledValue) {
                return new TotallyLegit($dice, (int)$rolledValue);
            },
            $dice
        );
    }
}
