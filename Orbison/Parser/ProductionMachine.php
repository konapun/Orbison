<?php
namespace Orbison\Parser;

use Orbison\Parser\PDA as PDA;
use Orbison\Parser\ProductionMachine\Production as Production;

/*
 * An abstraction for defining a grammar on top of a PDA
 *
 * TODO: need to think in terms of transitions and how to abstract them - start with hand-drawn PDA and create API
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
  function createProduction($name) { // $name is the production identifier from the grammar
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

    $pda = $this->pda;
var_dump($this->startProduction);
print "Adding transition from " . PDA::START . " on edge " . $this->startProduction . " to node " . $this->startProduction . "\n";
    $pda->addTransition(PDA::START, $this->startProduction, $this->startProduction);
    return $pda;
  }
}
?>
