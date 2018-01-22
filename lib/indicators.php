<?php
// This file will include all the indicators. For now I will leave each indicator in
// a separate file for debugging purposes.
// This will include dummy data for the meantime

$SHIFT_PARAM = 7;

/**
* Calculate RSI
*
* Function to calculate the RSI (Relative Strength Index)
*
* @param string $data    Historical close pricing data
* @return mixed
*/
function calculate_rsi($data) {
  $closing_prices = $upward_movement = $downward_movement = $avg_upward_movement = $avg_downward_movement = $relative_strength = [];

  // Append all closing_price data to an array in the past 14 days
  for($i=77; $i<=100; $i++)
  {
    $closing_prices[] = $data["Data"][$i]["close"];
  }

  // Calculate Upward/Downward Movement
  echo "CALCULATING UPWARD/DOWNWARD MOVEMENT... \n";
  for($i=0; $i<23; $i++)
  {
    $prev = $i - 1;
    if ($closing_prices[$i] > $closing_prices[$prev])
    {
      $upward_movement[] = $closing_prices[$i] - $closing_prices[$prev];
      $downward_movement[] = 0;
      echo round($upward_movement[$i], 2) . "      " . round($downward_movement[$i], 2) . "\n";
    }
    else
    {
      $downward_movement[] = $closing_prices[$prev] - $closing_prices[$i];
      $upward_movement[] = 0;
      echo round($upward_movement[$i], 2) . "      " . round($downward_movement[$i], 2) . "\n";
    }
  }
  echo "\n";


  // Array's containing last 10 elements of upward/downard movement
  $um_14 = array_slice($upward_movement, 1, 14);
  $dm_14 = array_slice($downward_movement, 1, 14);

  $avg_upward_movement[] = array_sum($um_14) / 14;
  $avg_downward_movement[] = array_sum($dm_14) / 14;
  echo "CALCULATING AVERAGE UPWARD/DOWNWARD TREND... \n";
  for($i=1; $i<11; $i++)
  {
    $prev = $i - 1;
    $um_c1 = $avg_upward_movement[$prev] * (13);
    $um_c2 = $upward_movement[$i + 14];
    $dm_c1 = $avg_downward_movement[$prev] * (13);
    $dm_c2 = $downward_movement[$i + 14];
    $avg_upward_movement[] = ($um_c1 + $um_c2) / 14;
    $avg_downward_movement[] = ($dm_c1 + $dm_c2) / 14;
    echo $avg_upward_movement[$prev] . "   " . $avg_downward_movement[$prev] . "\n";
  }
  echo "\n";

  echo "CALCULATING RELATIVE STRENGTH...\n";
  for($i=0; $i<10; $i++)
  {
    $relative_strength[] = $avg_upward_movement[$i] / $avg_downward_movement[$i];
    echo $relative_strength[$i] . "\n";
  }
  echo "\n";

  // calculate RSI
  $rsi = 100 - (100 / ($relative_strength[9] + 1));

  return $rsi;
}

/**
* Calculate Moving average convergence divergence (MACD)
*
* Function to calculate the Moving average convergence divergence (MACD)
*
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

  // Calculate the Signal line
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
  else
  {
    // Sell if MACD - Signal < 0
    return 0;
  }
}

/**
* Calculate Exponential Moving Average
*
* Function to calculate the Exponential Moving Average
*
* @return integer
*/
function calculateEMA($data, $emaParam, $priceIndex)
{
  $ema = [];
  $alpha = calculateFactor($emaParam);

  if($priceIndex < sizeof($data))
  {
    if($priceIndex > $emaParam - 1)
    {
      if($priceIndex-1 > - 1)
      {
        calculateEMA($data, $emaParam - 1, $priceIndex);

        $ema[] = ($data[$priceIndex] * $alpha) + ($ema[$priceIndex-1] * (1 - $alpha));
      }
    }
    elseif ($priceIndex == $emaParam - 1)
    {
      $sma = calculateSMA($data, $emaParam);

      if($sma != false)
      {
        $ema[] = $sma;
        $result = $sma;
      }
      else
      {
        $ema[] = 0;
      }
    }
  }
  return $ema;
}

/**
* Calculate the Simple Moving Average
*
* Function to calculate the Simple Moving Average
*
* @return integer
*/
function calculateSMA($data, $emaParam)
{
  $sum = 0;

  for($i=$emaParam; $i>-1; $i--)
  {
    $sum += $data[$i];
  }

  return $sum / $emaParam;
}

/**
* Calculate Exponential Moving Average Factor
*
* Function to calculate the Exponential Moving Average Factor
*
* @return integer
*/
function calculateFactor($emaParam)
{
  return 2 / ($emaParam + 1);
}




 ?>
