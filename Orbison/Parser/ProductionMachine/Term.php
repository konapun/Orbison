<?php
namespace Orbison\Parser\ProductionMachine;

class Term {
  private $factors;

  function __construct() {
    $this->factors = array();
  }

  function addFactor($factors) {
    if (!is_array($factors)) $factors = array($factors);

    foreach ($factors as $factor) {
      array_push($this->factors, $factor);
    }
    return $this;
  }

  function getFactors() {
    return $this->factors;
  }

}
?>
