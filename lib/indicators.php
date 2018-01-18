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
  for($i=23; i>0; i--)
  {
    $j = 0;
    $closing_prices[$j] = $data["Data"][$j]["close"];
    $j++;
  }

  // Calculate Upward/Downward Movement
  for($i=1; i<=23; i++)
  {
    $prev = $i--;

    if ($closing_prices[$i] > $closing_prices[$prev])
    {
      $upward_movement[$i] = $closing_prices[$i] - $closing_prices[$prev];
      $downward_movement[$i] = 0
    }
    else
    {
      $downward_movement[$i] = $closing_prices[$prev] - $closing_prices[$i];
      $upward_movement[$i] = 0
    }
  }

  // Array's containing first 14 elements of avg movement
  $um_14 = array_slice($upward_movement, 0, 14);
  $dm_14 = array_slice($downward_movement, 0, 14);

  $avg_upward_movement[0] = array_sum($um_14) / 14;
  $avg_downward_movement[0] = array_sum($um_14) / 14;
  for($i=1; i<10; i++)
  {
    $prev = $i--;
    $avg_upward_movement[$i] = ($avg_upward_movement[$prev] * (14 - 1) + $upward_movement[$i + 15]) / 14
    $avg_downward_movement[$i] = ($avg_downward_movement[$prev] * (14 - 1) + $downward_movement[$i + 15]) / 14
  }


  for($i=0; i<9; i++)
  {
    $relative_strength[$i] = $avg_upward_movement / $avg_downward_movement;
  }

  return 49;
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
