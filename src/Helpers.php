<?php
namespace MeadSteve\DiceApi\Helpers;

use http\Exception\RuntimeException;

function file_contents($path)
{
    $contents = \file_get_contents($path);
    if ($contents === false) {
        throw new \RuntimeException("Unable to load from {$path}");
    }
    return $contents;
}

function json_encode($data)
{
    $encoded = \json_encode($data);
    if ($encoded === false) {
        throw new \RuntimeException("Unable to encode json data");
    }
    return $encoded;
}
