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
  private $seen;

  function __construct() {
    $this->pda = new PDA();
    $this->startProduction = null;
    $this->productions = array();
    $this->seen = array();
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

    $acceptNodes = $this->walkProduction($startProduction, array(PDA::START));
    foreach ($acceptNodes as $acceptNode) {
      print "Transitioning from $acceptNode on ACCEPT TO ACCEPT\n";
      $pda->addTransition($acceptNode, PDA::ACCEPT, PDA::ACCEPT);
    }

    return $pda;
  }

  private function walkProduction($production, $incoming) {
    // Allow for recursive productions by only creating nodes and adding transitions the first time a production is encountered
    if (array_key_exists($production->getID(), $this->seen)) {
      print "Returning from SEEN production " . $production->getID() . " with outgoing " . $this->PRINT_ARRAY($this->seen[$production->getID()]) . "\n";
      return $this->seen[$production->getID()]; // FIXME still need to add transitions to
    }
    $this->seen[$production->getID()] = array();

    $pda = $this->pda;

    $carry = array(); // nodes which are carried to the next production
    $outgoing = array();
    foreach ($production->getTerms() as $term) {
      $currentNode = null;

      $matches = array(); // matches used for callback
      $incomingNodes = $incoming;
      foreach ($term->getFactors() as $factor) {
        if (!$factor->isTerminal()) {
          $transferNodes = $incomingNodes ? $incomingNodes : array($currentNode);
          $incomingNodes = $this->walkProduction($factor, $transferNodes);
          $carry = $incomingNodes;
          array_push($matches, $carry);
        }
        else {
          $factorID = $factor->getID();
          $factorNode = $pda->createNode($factorID);

          while ($node = array_pop($incomingNodes)) {
            print "Adding transition from $node on $factorID to $factorNode\n";
            $pda->addTransition($node, $factorID, $factorNode);
          }
          if ($currentNode) {
            print "Adding transition from $currentNode on $factorID to $factorNode\n";
            $pda->addTransition($currentNode, $factorID, $factorNode);
          }

          array_push($matches, $factorNode);
          $currentNode = $factorNode;
        }
      }
      if ($currentNode) {
        array_push($outgoing, $currentNode);
      }

    }

    if ($carry) {
      print "Overwriting " . $this->PRINT_ARRAY($outgoing) . " with " . $this->PRINT_ARRAY($carry) . "\n";
      $outgoing = $carry;
    }

    $this->seen[$production->getID()] = $outgoing;
    return $outgoing;
  }

  private function PRINT_ARRAY($array) {
    return array_reduce($array, function($a, $b) { return "$a $b"; }, "");
  }

}
?>
