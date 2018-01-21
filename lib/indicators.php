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
  for($i=7; $i<=30; $i++)
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
* @return mixed
*/
function calculate_macd($data) {
  $EMA_PARAMS = [12, 26, 9];
  $ema_factor = $slow_ema = $fast_ema = $difference = $signal = $closing_prices = [];

  for($i=0; $i<=27; $i++)
  {
    $closing_prices[] = $data["Data"][$i]["close"];
  }

  for($i=0; $i<3; $i++)
  {
    $ema_factor[] = calculateFactor($EMA_PARAMS[$i]);
  }

  // 12 and 26 day moving avg
  $t_day_avg = array_sum(array_slice($closing_prices, 0, 12)) / $EMA_PARAMS[0];
  $ts_day_avg = array_sum(array_slice($closing_prices, 0, 26)) / $EMA_PARAMS[1];
  return 1;
}

/**
* Calculate Exponential Moving Average Factor
*
* Function to calculate the Exponential Moving Average Factor
*
* @return integer
*/
function calculateFactor($ema_param)
{
  return 2 / ($ema_param + 1);
}

/**
* Calculate OBV
*
* Function to calculate the RSI (Relative Strength Index)
*
* @param string $data    Historical close pricing data
* @return mixed
*/
$CURRENT_CLOSE = 0;

function calculate_obv($data) {
  function obv($carry, $item) {
    global $CURRENT_CLOSE;
    if ($CURRENT_CLOSE == 0) {
      $CURRENT_CLOSE = $item["close"];
      $carry = 0;
    }
    if ($item["close"] == $CURRENT_CLOSE) {
      $CURRENT_CLOSE = $item["close"];
      return $carry;
    }
    ($item["close"] > $CURRENT_CLOSE) ? $carry += $item["volumefrom"] : $carry -= $item["volumefrom"];
    $CURRENT_CLOSE = $item["close"];
    return $carry;
  }
  return array_reduce($data["Data"], "obv");
}
?>
