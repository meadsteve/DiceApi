# DiceApi
[![Build Status](https://travis-ci.org/meadsteve/DiceApi.svg?branch=master)](https://travis-ci.org/meadsteve/DiceApi)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/meadsteve/DiceApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/meadsteve/DiceApi/?branch=master)

This is a semi joking api to simulate a pile of dice being thrown. The idea is fully inspired by/stolen from [deckofcardsapi.com](http://deckofcardsapi.com/). If you find any bugs or have feature requests, the project can be found on github at [DiceApi on GitHub](https://github.com/meadsteve/DiceApi/) or send me a tweet [@MeadSteve](https://twitter.com/MeadSteve).

## Rolling 
### Roll a single dice
```GET http://roll.diceapi.com/json/d6```

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
```GET http://roll.diceapi.com/json/d6/d20```

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
```GET http://roll.diceapi.com/json/2d6/d4```

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
## Response types
By default the API assumes you want an html response. You have other options though:

### text/html
This response type currently only supports d6 and d20 rolls.

```GET http://roll.diceapi.com/d6 [Accept: text/html]```

```GET http://roll.diceapi.com/html/d6```

Response:
```html
<img src="http://roll.diceapi.com/images/poorly-drawn/d6.4.png" />
```
![dice with 4 spots](http://roll.diceapi.com/images/poorly-drawn/d6.4.png)

### application/json

```GET http://roll.diceapi.com/d6 [Accept: application/json]```

```GET http://roll.diceapi.com/json/d6```
