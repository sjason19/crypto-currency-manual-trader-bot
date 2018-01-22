<?php
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
      $carry -= $item["volumefrom"];
      return $carry;
    }
    if ($item["close"] == $CURRENT_CLOSE) return $carry;
    ($item["close"] > $CURRENT_CLOSE) ? $carry += $item["volumefrom"] : $carry -= $item["volumefrom"];
    $CURRENT_CLOSE = $item["close"];
    return $carry;
  }
  return array_reduce($data["Data"], "obv");
}

?>
