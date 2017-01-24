<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Nonterminal as Nonterminal;
use Orbison\Parser\ProductionMachine\Factor as Factor;

class Term extends Nonterminal {
  private $production;
  private $id;
  private $factors;

  function __construct($production, $id) {
    $this->production = $production;
    $this->id = $id;
    $this->factors = array();
  }

  function addFactor($factors) {
    if (!is_array($factors)) $factors = array($factors);

    $production = $this->production;
    foreach ($factors as $factor) {
      $factorObj = $factor;

      $factorID = is_object($factor) ? $factor->getID() : $factor;
      if (!$production->isProduction($factorID)) {
        $factorObj = new Factor($factorID);
      }

      array_push($this->factors, $factorObj);
    }

    return $this;
  }

  function getFactors() {
    return $this->factors;
  }

  function getID() {
    return $this->id;
  }

  protected function getCollection() {
    return $this->getFactors();
  }

}
?>
