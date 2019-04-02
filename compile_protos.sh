#!/usr/bin/env bash
set -ex

mkdir -p proto_out
rm -rf ./src/Protos/
protoc --php_out=proto_out ./protos/dice.proto
cp -r ./proto_out/MeadSteve/DiceApi/Protos/ ./src
rm -rf ./proto_out/