<?php
include_once('./bitfinex.php');
include_once('./lib/indicators.php');
include_once('./security.php');

$coin = "XRP";
$phone = getPhone();;
$API_URL_HIST = "https://min-api.cryptocompare.com/data/histoday";
$API_URL_PRICE = "https://min-api.cryptocompare.com/data/price";

$qry_str_rsi = "?fsym=$coin&tsym=BTC&limit=30&e=CCCAGG";
$ch = curl_init();

// Fetch Coin Information
curl_setopt($ch, CURLOPT_URL, $API_URL_HIST . $qry_str_rsi);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
$content = trim(curl_exec($ch));
curl_close($ch);

$content = json_decode($content, true);

// ***** CALCUATING RSI ******
$rsi = calculate_rsi($content);
echo "RSI: " . $rsi . "\n";

// ***** CALCUATING MACD ******
$macd = calculate_macd($content);
echo "MACD: " . $macd . "\n";



// Fetch Coin price
$qry_str_price = "?fsym=$coin&tsyms=USD";
$ch = curl_init();
echo "COIN PRICE API: $API_URL_PRICE" . $qry_str_price . "\n";

// Fetch Coin Information
curl_setopt($ch, CURLOPT_URL, $API_URL_PRICE . $qry_str_price);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
$content = trim(curl_exec($ch));
curl_close($ch);

$content = json_decode($content, true);
$price = $content["USD"];
echo "PRICE: " . $price . "\n";

if ($rsi < 30)
{
  $message = "Indicators showing good signals to buy: " . $coin . " ($" . $price . ")" . "\n";
}
else if ($rsi > 60)
{
  $message = "Indicators showing good signals to sell: " . $coin . " ($" . $price . ")" . "\n";
}
else
{
  $message = "Indicators not showing any buy/sell signals: " . $coin . " ($" . $price . ")" . "\n";
}
  // SMS alert
  $api_key = getKey();
  $api_secret = getSecret();
  $ca_number = getCaNumber();

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL,"https://rest.nexmo.com/sms/json");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,
              "api_key=$api_key&api_secret=$api_secret&to=1$phone&from=$ca_number&text=$message");

  // receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $server_output = curl_exec ($ch);

  curl_close ($ch);

?>
