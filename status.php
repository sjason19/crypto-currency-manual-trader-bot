<?php


$currency = @$_GET['currency'] ? htmlspecialchars($_GET['currency']) : 'usd';
include_once('./bitfinex.php');
include_once('./lib/indicators.php');

$coin = "XLM";
$bfx = new Bitfinex($config['api_key'], $config['api_secret']);

$message = "No update";

if (calculate_rsi() < 50 || calculate_macd() > 0)
{
  $message = "Good time to sell: " . $coin . "\n";
}


// Send email notification to buy/sell
$to = "s.jay882@gmail.com";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";
if (mail($to, $subject, $body)) {
echo("<p>Email successfully sent!</p>");
} else {
echo("<p>Email delivery failed…</p>");
}
