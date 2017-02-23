<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Nonterminal as Nonterminal;
use Orbison\Parser\ProductionMachine\Factor as Factor;

class Term extends Nonterminal {
  private $production;
  private $id;
  private $factors;
  private $matchCallback; // TODO - these will register with the production machine since the production machine will be responsible for finding matches

  function __construct($production, $id) {
    $this->production = $production;
    $this->id = $id;
    $this->factors = array();
    $this->matchCallback = function() {};
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

  /*
   * Define a function to run when this term is matched. The callback will be
   * used to execute code or build an AST.
   */
  function onMatch($fn) {
    $this->matchCallback = $fn;
  }

  function triggerMatch($matches) {
    $callback = $this->matchCallback;
    $callback($matches);
  }

  function getFactors() {
    return $this->factors;
  }

  function getID() {
    return $this->id;
  }

  function getFirstTerminals() {
    $factors = $this->getFactors();

    $firstTerminals = array();
    if (count($factors) > 0) {
      $firstFactor = $factors[0];
      $firstTerminals = array_merge($firstTerminals, $firstFactor->getFirstTerminals());
    }

    return $firstTerminals;
  }
}
?>
