<?php
namespace MeadSteve\DiceApi\Helpers;

function file_contents(string $path): string
{
    $contents = \file_get_contents($path);
    if ($contents === false) {
        throw new \RuntimeException("Unable to load from {$path}");
    }
    return $contents;
}

function json_encode($data): string
{
    $encoded = \json_encode($data);
    if ($encoded === false) {
        throw new \RuntimeException("Unable to encode json data");
    }
    return $encoded;
}
