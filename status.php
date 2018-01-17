<?php
include_once('./bitfinex.php');
include_once('./lib/indicators.php');

$qry_str = "?fsym=BTC&tsyms=USD&ts=1452680400";
$ch = curl_init();

// Set query data here with the URL
curl_setopt($ch, CURLOPT_URL, 'https://min-api.cryptocompare.com/data/pricehistorical' . $qry_str);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
$content = json_decode(trim(curl_exec($ch)));
curl_close($ch);

var_dump($content);


if (calculate_rsi() < 50 || calculate_macd() > 0)
{
  $message = "Good time to sell: " . $coin . "\n";
}
