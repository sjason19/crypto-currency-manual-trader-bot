<?php
/**
* Calculate Moving average convergence divergence (MACD)
*
* Function to calculate the Moving average convergence divergence (MACD)
*
* @param string $data    Historical close pricing data
* @param array $EMA_PARAMS    An array that contants the Fast, Slow and Signal EMA values respectively
* @return integer
*/
function calculate_macd($data, $EMA_PARAMS) {
  $ema_factor = $slow_ema = $fast_ema = $macd = $closing_prices = $diff = $ema_9 = $hist = [];

  for($i=0; $i<=100; $i++)
  {
    $closing_prices[] = $data["Data"][$i]["close"];
  }

  // Initialize EMA Multiplying Factors
  for($i=0; $i<3; $i++)
  {
    $ema_factor[] = calculateFactor($EMA_PARAMS[$i]);
  }

  // Calculate 12 and 26 day exponential moving average
  $fast_ema[0] = array_sum(array_slice($closing_prices, 0, $EMA_PARAMS[0])) / $EMA_PARAMS[0];
  $slow_ema[0] = array_sum(array_slice($closing_prices, 0, $EMA_PARAMS[1])) / $EMA_PARAMS[1];

  for ($i=1; $i<sizeof($closing_prices)-$EMA_PARAMS[0]; $i++)
  {
    $prev = $i-1;
    $fast_ema[] = (($closing_prices[$i+$EMA_PARAMS[0]] - $fast_ema[$prev]) * $ema_factor[0]) + $fast_ema[$prev];
    if ($i<sizeof($closing_prices)-$EMA_PARAMS[1])
    {
      $slow_ema[] = (($closing_prices[$i+$EMA_PARAMS[1]] - $slow_ema[$prev]) * $ema_factor[1]) + $slow_ema[$prev];
    }
  }

  // Calculate the difference between the 12 day and 26 day expoential moving averages
  for($i=0; $i<sizeof($closing_prices)-$EMA_PARAMS[1]; $i++)
  {
    $diff[] = ($fast_ema[$i+$EMA_PARAMS[1]-$EMA_PARAMS[0]] - $slow_ema[$i]);
//    echo "diff: " . $diff[$i] . "\n";
  }

  // Calculate the Signal Line
  // First Index of Signal Line is the average of the difference between fast - slow ema
  $ema_9[0] = array_sum(array_slice($diff, 0, $EMA_PARAMS[2])) / $EMA_PARAMS[2];

  for($i=1; $i<sizeof($diff)-$EMA_PARAMS[2]; $i++)
  {
    $prev = $i-1;
    $ema_9[$i] = (($diff[$EMA_PARAMS[2] + $i] - $ema_9[$prev]) * $ema_factor[2]) + $ema_9[$prev];
  // echo "ema_9: " . $ema_9[$prev] . "\n";
  }
  // echo "ema_9: " . $ema_9[sizeof($ema_9)-1] . "\n";

  for($i=0; $i<=sizeof($diff)-$EMA_PARAMS[2]; $i++)
  {
    $hist[] = $diff[$i + $EMA_PARAMS[2] - 1] - $ema_9[$i-1];
    echo "macd_hist: " . $hist[$i] . "\n";
  }

  if($hist[sizeof($hist)-1] > 0 && $hist[sizeof($hist)-2] < 0)
  {
    // Buy if MACD - Signal > 0
    // echo "current hist: " . $hist[sizeof($hist)-1] . " > 0 && " . $hist[sizeof($hist)-2] . " < 0" . "\n";
    return 1;
  }
  elseif($hist[sizeof($hist)-1] < 0 && $hist[sizeof($hist)-2] > 0)
  {
    // Sell if MACD - Signal < 0
    return 0;
  }
  return -99;
}

/**
* Calculate Exponential Moving Average Factor
*
* Function to calculate the Exponential Moving Average Factor
*
* @param integer $emaParam   The EMA type (Fast, Slow, or Signal)
* @return integer
*/
function calculateFactor($emaParam)
{
  return 2 / ($emaParam + 1);
}


 ?>
