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
    /**
     * @var UrlDiceGenerator
     */
    private $diceGenerator;

    /**
     * @var DiceCounter
     */
    private $diceCounter;

    /**
     * @var RendererCollection
     */
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

    public function __invoke(Request $request, Response $response, $args)
    {
        return $this->getDice($request, $response, $args);
    }

    public function getDice(Request $request, Response $response, $args): Response
    {
        $diceResponse = $response
            ->withHeader("cache-control", "no-cache")
            ->withHeader('Access-Control-Allow-Origin', '*');
        try {
            $diceCollection = $this->diceCollectionFromRequest($request, $args);
            $this->diceCounter->count($diceCollection);
            return $this->writeAppropriateFormatResponse($request, $diceResponse, $diceCollection);
        } catch (UncreatableDiceException $creationError) {
            return $diceResponse->withStatus(400)
                ->write("Unable to roll diceCollection: " . $creationError->getMessage());
        } catch (UnrenderableDiceException $renderError) {
            return $diceResponse->withStatus(400)
                ->write("Unable to render request: " . $renderError->getMessage());
        }
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

    /**
     * @param Request $request
     * @param Response $response
     * @param Dice[] $diceCollection
     * @return Response
     */
    private function writeAppropriateFormatResponse(Request $request, Response $response, array $diceCollection)
    {
        $acceptHeader = $request->getHeader('accept');
        $requestedContentType = $acceptHeader[0];
        try {
            $renderer = $this->rendererCollection->newForAcceptType($requestedContentType);
            return $response->write($renderer->renderDice($diceCollection))
                ->withHeader("Content-Type", $renderer->contentType());
        } catch (UnknownRendererException $error) {
            return $response->withStatus(406)
                ->write("Not sure how to respond with: " . $requestedContentType);
        }
    }

    /**
     * @param Dice[] $diceCollection
     * @param Request $request
     * @return Dice[]
     */
    private function makeDiceTotallyLegit(array $diceCollection, Request $request)
    {
        $rolledValue = $request->getHeader('totally-legit')[0];
        return array_map(
            function (Dice $dice) use ($rolledValue): Dice {
                return new TotallyLegit($dice, (int) $rolledValue);
            },
            $diceCollection
        );
    }

    /**
     * @param Request $request
     * @param mixed[] $args
     * @return Dice[]
     */
    private function diceCollectionFromRequest(Request $request, array $args)
    {
        $diceCollection = $this->diceGenerator->diceFromUrlString($args['dice']);
        if ($request->hasHeader('totally-legit')) {
            return $this->makeDiceTotallyLegit($diceCollection, $request);
        }
        return $diceCollection;
    }
}
