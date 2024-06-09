# Cricket API

Cricket API Build using Slim Framework 4 Skeleton Application.  

> WIP 🚧  

## Built Using

- PHP
- Silm Framework
- symfony DOM crawler and CSS selector
- Guzzle - PHP HTTP client
- Composer for install and Manage PHP packages  

## Setup

- Download this repo or clone
- Goto folder `data` and install required packages

```sh
cd data
composer install
```

- Testing

```sh
composer start
```

- Open URL

```sh
http://localhost:6005/data/web/score?id=87633
```

- Sample JSON Response

```json
{
  "title": "Oman vs Scotland, 20th Match, Group B",
  "update": "Oman opt to bat",
  "livescore": "OMAN 55/2 (6.2)",
  "match_date": "Sunday, 09 Jun 2024 - 10:30:00 PM",
  "runrate": "8.68",
  "current_batsmen": [
    {
      "name": "Zeeshan Maqsood",
      "runs": "2",
      "balls": "2",
      "strike_rate": "100"
    }
  ],
  "current_bowler": [
    {
      "name": "Mark Watt",
      "overs": "1.2",
      "runs": "5",
      "wickets": "0"
    }
  ]
}
```

## LICENSE

MIT
