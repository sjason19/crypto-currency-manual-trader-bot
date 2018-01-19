<?php
include_once('./bitfinex.php');
include_once('./lib/indicators.php');
include_once('./security.php');

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
echo $rsi;

if ($rsi < 50 || calculate_macd() > 0)
{
  $message = "Good time to sell: " . $coin . "\n";
}

// SMS alert
$api_key = getKey();
$api_secret = getSecret();
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://rest.nexmo.com/sms/json");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "api_key=$api_key&api_secret=$api_secret&to=16043184740&from=12262101784&text='Hello from Nexmo'");

// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

?>
