# crypto-currency-manual-trader-bot
## Current Status
This bot is still in progress. As a regular crypto currency trader I find myself analyzing way too many cryptocurrencies. The goal for this project is to calculate indicators such as RSI, MACD and bollinger to determine good entry/exit points for my crypto currency portfolio. If my bot finds good entry/exit points for coins that I'm watching, I aim to send me a text or email notification. Right now I only have the Bitfinex and Crypto-compare API set up but I aim to achieve setting up the Binance and Kucoin API's to query other coin data.

## Setup

```
git clone https://github.com/sjason19/crypto-currency-manual-trader-bot.git

cd crypto-currency-manual-trader-bot

mv security-example.php security.php
```

Sign up for API keys: [https://dashboard.nexmo.com/sign-up](https://dashboard.nexmo.com/sign-up) & enter the keys into security.php