<?php
namespace Orbison\Parser;

use Orbison\Parser\ProductionMachine\Production as Production;

/*
 * An abstraction for defining a grammar on top of a PDA
 */
class ProductionMachine {
  private $pda;
  private $productions;

  function __construct($pda) {
    $this->pda = $pda;
    $this->productions = array();
  }

  function addProduction($name) {
    $production = new Orbison\Parser\ProductionMachine\Production($this->pda);
    $this->productions[$name] = $production;
    return $production;
  }

  function getAutomaton() {
    return $this->pda;
  }
}
?>
