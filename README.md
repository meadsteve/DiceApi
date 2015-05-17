# DiceApi
[![Build Status](https://travis-ci.org/meadsteve/DiceApi.svg?branch=master)](https://travis-ci.org/meadsteve/DiceApi)

This is a semi joking api to simulate a pile of dice being thrown. The idea is fully inspired by/stolen from [deckofcardsapi.com](http://deckofcardsapi.com/). If you find any bugs or have feature requests, the project can be found on github at [DiceApi on GitHub](https://github.com/meadsteve/DiceApi/) or send me a tweet [@MeadSteve](https://twitter.com/MeadSteve).

## Usage 
### Roll a single dice
```GET http://roll.diceapi.com/d6```

Response:
```json
{
  "success":true,
  "dice":[
    {"value":2,"size":"d6"}
  ]
}
```


### Roll multiple dice
```GET http://roll.diceapi.com/d6/d20```

Response:
```json
{
  "success":true,
  "dice":[
    {"value":2,"size":"d6"},
    {"value":18,"size":"d20"}
  ]
}
```

### Roll batches of dice
```GET http://roll.diceapi.com/2d6/d4```

Response:
```json
{
  "success":true,
  "dice":[
    {"value":2,"size":"d6"},
    {"value":4,"size":"d6"},
    {"value":3,"size":"d4"}
  ]
}
```
