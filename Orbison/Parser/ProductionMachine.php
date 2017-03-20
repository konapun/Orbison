<?php
namespace Orbison\Parser;

use Orbison\Parser\PDA as PDA;
use Orbison\Parser\ProductionMachine\Production as Production;
use Orbison\Parser\ProductionMachine\Linker as Linker;

/*
 * An abstraction for defining a grammar on top of a PDA
 */
class ProductionMachine {
  private $linker;
  private $startProduction;
  private $productions;

  function __construct() {
    $this->linker = new Linker();
    $this->startProduction = null;
    $this->productions = array();
  }

  /*
   * Add a new production to the machine. The production registers with a name
   * so that transitions to other productions can be established before they're
   * created. The resulting production is just an abstraction which modifies the
   * underlying machine.
   */
  function createProduction($name) { // $name is the production identifier from the grammar
    $production = new Production($this, $name);
    $this->productions[$name] = $production;
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
    return $this->linker->link($this->productions[$this->startProduction]);
  }

}
?>
