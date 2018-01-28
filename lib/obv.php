<?php

$CURRENT_CLOSE = 0;

function obv($carry, $item) {
  global $CURRENT_CLOSE;
  if ($CURRENT_CLOSE == 0) {
    $CURRENT_CLOSE = $item["close"];
    $carry = 0;
    return $carry;
  }
  if ($item["close"] == $CURRENT_CLOSE) return $carry;
  ($item["close"] > $CURRENT_CLOSE) ? $carry += $item["volumefrom"] : $carry -= $item["volumefrom"];
  $CURRENT_CLOSE = $item["close"];
  return $carry;
}

/**
* Calculate OBV
*
* Function to calculate the OBV (On Balance Volume)
*
* @param string $data    Historical close pricing and volume data
* @return mixed
*/

function calculate_obv($data) {
  return array_reduce($data["Data"], "obv");
}

?>
