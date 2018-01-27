<?php
class Configuration {
  private $key;
  private $secret;
  private $phone;
  private $virtual;

  public function __construct($key = "Enter Key", $secret = "Enter Secret", $phone = "Enter Phone Number", $virtual = "Enter Virtual Nexmo Number")
  {
    $this->key = $key;
    $this->secret = $secret;
    $this->phone = $phone;
    $this->virtual = $virtual;
  }

  function getKey()
  {
    return $this->key;
  }

  function getSecret()
  {
    return $this->secret;
  }

  function getPhone()
  {
    return $this->phone;
  }

  function getVirtual()
  {
    return $this->virtual;
  }
}
 ?>
