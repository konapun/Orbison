<?php
namespace Orbison\Parser;

use Orbison\Parser\ProductionMachine\Production as Production;

/*
 * An abstraction for defining a grammar on top of a PDA
 */
class ProductionMachine {
  private $pda;
  private $startProduction;
  private $productions;

  function __construct($pda) {
    $this->pda = $pda;
    $this->startProduction = null;
    $this->productions = array();
  }

  /*
   * Add a new production to the machine. The production registers with a name
   * so that transitions to other productions can be established before they're
   * created. The resulting production is just an abstraction which modifies the
   * underlying machine.
   */
  function createProduction($name) {
    $node = $this->pda->createNode($name);

    $production = new Production($this->pda, $node);
    $this->productions[$name] = $node;
    if ($this->startProduction === null) {
      $this->startProduction = $name;
    }
    return $production;
  }

  /*
   * Piece together the productions by creating transitions for nodes which
   * reference other productions
   */
  function exportPDA() {
    $production = $this->productions[$this->startProduction];

    return $this->pda;
  }
}
?>
