<?php

namespace MeadSteve\DiceApi\Renderer;

class RendererCollection
{
    /**
     * @var DiceRenderer[]
     */
    private $renderers;

    /**
     * RendererCollection constructor.
     * @param DiceRenderer[] $renderers
     */
    public function __construct(array $renderers)
    {
        foreach ($renderers as $renderer) {
            $this->renderers[$renderer->contentType()] = $renderer;
        }
    }

    /**
     * @param string $acceptTypes
     * @return DiceRenderer
     * @throws UnknownRendererException
     */
    public function newForAcceptType($acceptTypes)
    {
        foreach (explode(",", $acceptTypes) as $acceptType) {
            if (array_key_exists($acceptType, $this->renderers)) {
                return $this->renderers[$acceptType];
            }
        }
        throw new UnknownRendererException;
    }

    /**
     * Returns key value pairs mapping a url prefix to a handled content type
     * so ["json" => "application/json", "etc" => "..."]
     * @return string[]
     */
    public function contentTypesForPaths() : array
    {
        $types = [];
        foreach ($this->renderers as $renderer) {
            $types[$renderer->urlPrefix()] = $renderer->contentType();
        }
        return $types;
    }
}
