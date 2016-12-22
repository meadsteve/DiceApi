<?php

namespace MeadSteve\DiceApi\RequestHandler;

use MeadSteve\DiceApi\Counters\DiceCounter;
use MeadSteve\DiceApi\Dice;
use MeadSteve\DiceApi\UrlDiceGenerator;
use MeadSteve\DiceApi\Dice\UncreatableDiceException;
use MeadSteve\DiceApi\DiceDecorators\TotallyLegit;
use MeadSteve\DiceApi\Renderer\RendererCollection;
use MeadSteve\DiceApi\Renderer\UnknownRendererException;
use MeadSteve\DiceApi\Renderer\UnrenderableDiceException;
use Slim\Http\Request;
use Slim\Http\Response;

class DiceRequestHandler
{

    private $diceGenerator;
    private $diceCounter;
    private $rendererCollection;

    public function __construct(
        UrlDiceGenerator $diceGenerator,
        RendererCollection $rendererCollection,
        DiceCounter $diceCounter
    ) {
        $this->diceGenerator = $diceGenerator;
        $this->rendererCollection = $rendererCollection;
        $this->diceCounter = $diceCounter;
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

    /**
     * Returns key value pairs mapping a url prefix to a handled content type
     * so ["json" => "application/json", "etc" => "..."]
     * @return string[]
     */
    public function contentTypesForPaths() : array
    {
        return $this->rendererCollection->contentTypesForPaths();
    }

    private function writeAppropriateFormatResponse(Request $request, Response $response, $dice)
    {
        $acceptHeader = $request->getHeader('accept');
        $requestedContentType = $acceptHeader[0];
        try {
            $renderer = $this->rendererCollection->newForAcceptType($requestedContentType);
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
        $rolledValue = $request->getHeader('totally-legit')[0];
        return array_map(
            function (Dice $dice) use ($rolledValue) {
                return new TotallyLegit($dice, (int) $rolledValue);
            },
            $dice
        );
    }
}
