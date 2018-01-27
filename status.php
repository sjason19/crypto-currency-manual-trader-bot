<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

include_once('./bitfinex.php');
include_once('./lib/macd.php');
include_once('./lib/rsi.php');
include_once('./lib/obv.php');
include_once('./Configuration.php');

$coins = [];

echo "What coin would you like to analyze? Enter ticker to continue (ie: ETH): " . "\n";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(strlen(trim($line)) > 5){
    echo "Invalid Ticker!\n";
    return;
}
else
{
  $coins[] = $line;
}
echo "\n";

while(1)
{
  echo "Enter next coin (Press enter if finished): " . "\n";
  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  if(strlen(trim($line)) > 5){
      echo "Invalid Ticker! Please Retry\n";
  }
  else
  {
    $coins[] = $line;
  }
  if(strlen(trim($line)) == 0){
      break;
  }
}
echo "\n";
echo "Thank you, continuing...\n";
echo $coins[0] . "\n";

$config = new Configuration();
$phone = $config->getPhone();
$virtual = $config->getVirtual();
$PERIOD = 100;
$OBV_PERIOD = 21;

$BASE_URL = "https://min-api.cryptocompare.com/data/";
$DAY_HIST = "histoday";
$PRICE = "price";

sleep(1);
// $coin = "ETH"

for ($i=0; $i < sizeof($coins); $i++)
{
  $coin = $coins[$i];
// Fetch Coin Information
$client = new Client(['base_uri' => $BASE_URL, 'timeout'  => 3.0,]);
$qry_str_day_hist = "?fsym=$coin&tsym=BTC&limit=$PERIOD&e=CCCAGG";
$qry_str_day_hist_obv = "?fsym=$coin&tsym=BTC&limit=$OBV_PERIOD&e=CCCAGG";
$response = $client->request('GET', $DAY_HIST . $qry_str_day_hist);
$content = json_decode($response->getBody(), true);
echo $content;

// ***** CALCUATING RSI ******
$rsi = calculate_rsi($content);
echo "RSI: " . $rsi . "\n";

// ***** CALCUATING MACD ******
$macd = calculate_macd($content, $EMA_PARAMS = [12, 26, 9]);
echo "MACD: " . $macd . "\n";

$response = $client->request('GET', $DAY_HIST . $qry_str_day_hist_obv);
$content = json_decode($response->getBody(), true);

// ***** CALCUATING OBV ******
// $obv = calculate_obv($content);
// echo "OBV: " . $obv . "\n";

// Fetch Coin price
$qry_str_price = "?fsym=$coin&tsyms=USD";
$response = $client->request('GET', $PRICE . $qry_str_price);
$coin_price =  json_decode($response->getBody(), true)["USD"];
echo "COIN PRICE API: $API_URL_PRICE" . $coin_price . "\n";

// Buy if RSI < 30 AND MACD == 1
// Sell if RSI > 60 MACD == 0
if ($rsi < 30)
{
  $message = "RSI Indicator showing good signals to buy: " . $coin . " ($" . $coin_price . ")" . "\n";
}
else if ($rsi > 60)
{
  $message = "RSI Indicator showing good signals to sell: " . $coin . " ($" . $coin_price . ")" . "\n";
}
else
{
  $message = "RSI Indicator not showing any buy/sell signals: " . $coin . " ($" . $coin_price . ")" . "\n";
}

if ($macd == 1)
{
  $message1 = "MACD Indicator showing good signals to buy: " . $coin . " ($" . $coin_price . ")" . "\n";
}
else if ($macd == 0)
{
  $message1 = "MACD Indicator showing good signals to sell: " . $coin . " ($" . $coin_price . ")" . "\n";
}
else
{
  $message1 = "MACD Indicator not showing any buy/sell signals: " . $coin . " ($" . $coin_price . ")" . "\n";
}


  // SMS alert
  $api_key = $config->getKey();
  $api_secret = $config->getSecret();
  $virtual = $config->getVirtual();
  $NEXMO_URL = 'https://rest.nexmo.com/sms/json';

  // First Indicator Message
  $client = new Client();

  $response = $client->post($NEXMO_URL, [
    GuzzleHttp\RequestOptions::JSON => [
      'api_key' => $api_key,
      'api_secret' => $api_secret,
      'to' => '1' . $phone,
      'from' => '1' . $virtual,
      'text' => $message
      ]
  ]);

  sleep(1);

  // Second Indicator Message
  $response = $client->post($NEXMO_URL, [
    GuzzleHttp\RequestOptions::JSON => [
      'api_key' => $api_key,
      'api_secret' => $api_secret,
      'to' => '1' . $phone,
      'from' => '1' . $virtual,
      'text' => $message1
      ]
  ]);
}
?>
