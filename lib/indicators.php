<?php
// This file will include all the indicators. For now I will leave each indicator in
// a separate file for debugging purposes.
// This will include dummy data for the meantime

/**
* Calculate RSI
*
* Function to calculate the RSI (Relative Strength Index)
*
* @param string $data    Historical close pricing data
* @return mixed
*/
function calculate_rsi($data) {
  $closing_prices = [];
  $upward_movement = [];
  $downward_movement = [];
  $avg_upward_movement = [];
  $avg_downward_movement = [];
  $relative_strength = [];

  // Append all closing_price data to an array in the past 14 days
  for($i=0; $i<=23; $i++)
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
function calculate_macd() {
  return 1;
}




 ?>
