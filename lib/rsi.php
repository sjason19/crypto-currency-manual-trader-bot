<?php

include_once('./config.php');

// CONSTANTS
$RSI_upper = 70;
$RSI_lower = 35;

// INITIALIZATION
$close_now = 0;
$close_previous = 0;

/**
* Calculate RSI
*
* Function to calculate the RSI (Relative Strength Index)
*
* @param string $symbol    The name of the symbol (see `/symbols`).
* @return mixed
*/
public function calculate_rsi($data) {
  $request = $this->endpoint('stats', $symbol);

  return $this->send_public_request($request);
}
