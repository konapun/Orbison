<?php
namespace Orbison\Parser;

use Orbison\Parser\PDA as PDA;
use Orbison\Parser\ProductionMachine\Production as Production;

/*
 * An abstraction for defining a grammar on top of a PDA
 */
class ProductionMachine {
  private $pda;
  private $startProduction;
  private $productions;
  private $pdaNodeMap;

  function __construct() {
    $this->pda = new PDA();
    $this->startProduction = null;
    $this->productions = array();
    $this->pdaNodeMap = array();
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
    $pda = $this->pda;
    $startProduction = $this->productions[$this->startProduction];
    $firstTerminals = $startProduction->getFirstTerminals();

    foreach ($firstTerminals as $terminal) {
      $terminalID = $terminal->getID();

      $startNode = $this->createNode($terminalID);
      $pda->addTransition(PDA::START, $terminalID, $startNode);
    }

    $this->walkProduction($startProduction, array());
    return $pda;
  }

  /*
   * Walks down a production, building a network
   */
  private function walkProduction($production, $seen) {
    $pda = $this->pda;

    print "-PRODUCTION " . $production->getID() . "\n";
    $seen[$production->getID()] = true;
    foreach ($production->getTerms() as $term) { // terms are branches in a production
      print "-- BRANCH\n";
      foreach ($term->getFactors() as $factor) {

        if ($production->isProduction($factor->getID())) {
          if (!array_key_exists($factor->getID(), $seen)) {
            $this->walkProduction($factor, $seen);
          }
        }
        else {
          print "--- FACTOR " . $factor->getID() . "\n";
        }
      }
    }
  }

  private function buildProduction($production) {
    $pda = $this->pda;
    $productionFirstTerminals = $production->getFirstTerminals();
    var_dump($productionFirstTerminals);
    $productionNode = $this->createNode($production->getID());

    foreach ($production->getTerms() as $term) { // terms are branches in a production
      $currentFactor = null;
      foreach ($term->getFactors() as $factor) {
        $factorID = $factor->getID();

        $factorNode = $this->createNode($factorID);
        if (!$currentFactor) {
          print "(BRANCH) Adding transition from $productionNode on $factorID to $factorNode\n";
          $pda->addTransition($productionNode, $factorID, $factorNode);
        }
        else {
          print "(FACTOR) Adding transition from $currentFactor on $factorID to $factorNode\n";
          $pda->addTransition($currentFactor, $factorID, $factorNode);
        }

        $currentFactor = $factorNode;
      }
    }

    return $productionNode;
  }

  /*
   * Return whether or not a node with id $id exists within the PDA
   */
  private function hasNode($id) {
    return array_key_exists($id, $this->pdaNodeMap);
  }

  /*
   * Create a new node in the PDA and add it to the production machine's
   * registry
   */
  private function createNode($id) {
    $node = $this->pda->createNode($id);
    $this->pdaNodeMap[$id] = $node;

    return $node;
  }

}
?>
