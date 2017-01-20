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
    $startNode = $this->getOrCreateNode($this->startProduction);

    $currentNode = $startNode;
    foreach ($this->productions as $productionName => $production) {
      $this->buildProduction($production);
    }

    print "(START) Adding transition from PDA_START on" . $this->startProduction . " to $startNode\n";
    $pda->addTransition(PDA::START, $this->startProduction, $startNode);

    $this->pruneProductions();
    return $pda;
  }

  /*
   * Productions are added as nodes to the PDA initially and then pruned, which
   * forwards their edges to connecting nodes, thus eliminating the need to
   * recursively traverse production rules to locate nonterminals
   */
  private function pruneProductions() {
    $pda = $this->pda;

    foreach ($this->productions as $productionName => $production) {
      // TODO
      // $pda->pruneNode($productionNode);
      print "Pruning $productionName\n";
    }
  }

  private function buildProduction($production) {
    $pda = $this->pda;
    $productionNode = $this->getOrCreateNode($production->getID());

    foreach ($production->getTerms() as $term) { // terms are branches in a production
      $currentFactor = null;
      foreach ($term->getFactors() as $factor) {
        $factorNode = $this->getOrCreateNode($factor);
        if (!$currentFactor) {
          print "(BRANCH) Adding transition from $productionNode on $factor to $factorNode\n";
          $pda->addTransition($productionNode, $factor, $factorNode);
        }
        else {
          print "(FACTOR) Adding transition from $currentFactor on $factor to $factorNode\n";
          $pda->addTransition($currentFactor, $factor, $factorNode);
        }

        $currentFactor = $factorNode;
      }
    }

    return $productionNode;
  }

  private function getOrCreateNode($productionID) {
    if (array_key_exists($productionID, $this->pdaNodeMap)) {
      return $this->pdaNodeMap[$productionID];
    }

    $pdaNode = $this->pda->createNode($productionID);
    $this->pdaNodeMap[$productionID] = $pdaNode;
    return $pdaNode;
  }

}
?>
