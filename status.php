<?php
include_once('./bitfinex.php');
include_once('./lib/indicators.php');

$qry_str = "?fsym=BTC&tsym=USD&limit=23&e=CCCAGG";
$ch = curl_init();

// Set query data here with the URL
curl_setopt($ch, CURLOPT_URL, 'https://min-api.cryptocompare.com/data/histoday' . $qry_str);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
$content = trim(curl_exec($ch));
curl_close($ch);

$content = json_decode($content, true);
var_dump($content["Data"][0]["close"]);

$rsi = calculate_rsi($content);


if ($rsi < 50 || calculate_macd() > 0)
{
  $message = "Good time to sell: " . $coin . "\n";
}
