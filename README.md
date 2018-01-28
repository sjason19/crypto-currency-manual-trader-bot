# Crypto Currency Manual Trading Bot
## Current Status
This bot is still in progress. Right now only MACD and RSI values are incorporated for buy/sell signals.

As a regular crypto currency trader I find myself analyzing way too many cryptocurrencies. At the moment this bot is manual because utilizing only RSI/MACD values is not reliable enough to make an automatic trade. However, this bot can be run via cron jobs to automatically send you an SMS notification if your coins are showing good signals using the NEXMO API.

As this bot becomes more versatile in terms of indicators, trading strategies etc.. I want to develop a REST API other people can call to use in their development!

## Setup

### Step 1) Requirements
- macOS 10 / Linux / Windows
- PHP (version 5.5 or higher)

### Step 2) Install the bot

In your console run:

```
git clone https://github.com/sjason19/crypto-currency-manual-trader-bot.git
cd crypto-currency-manual-trader-bot
```

[MAC] Install dependencies with Composer
```
curl -sS https://getcomposer.org/installer | php 
php composer.phar install
```

[WINDOWS] Install dependencies with Composer
```
[Download Composer.exe](https://getcomposer.org/download/)
composer install 
```

Now time to set up your configurations and API keys:
```
cp Configuration-example.php Configuration.php
```

- View and edit `Configuration.php`.
- You must add your Nexmo API to enable SMS notifications.
- Sign up for API keys: [https://dashboard.nexmo.com/sign-up](https://dashboard.nexmo.com/sign-up) & enter the keys, your phone number and the virtual phone number (given by NEXMO) into security.php


## Roadmap

Jan 2018:
- Finish Issues CC1 - CC7 and possibly incorporate many more indicators

Feb 2018:
- Incorporate real trading strategies and incorporate the necessary indicators

March 2018:
- Incorporate popular exchange API's such as Binance, Bitfinex, Kucoin, and HITBtc and many others

April 2018:
- Start using trading strategies to make automatic trades on cryptocurrency exchanges

