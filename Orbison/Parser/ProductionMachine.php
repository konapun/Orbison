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
    // $firstTerminals = $startProduction->getFirstTerminals();

    // foreach ($firstTerminals as $terminal) {
      // $terminalID = $terminal->getID();

      // $startNode = $this->createNode($terminalID);
      // $pda->addTransition(PDA::START, $terminalID, $startNode);
    // }

    $acceptNodes = $this->walkProduction($startProduction, array(PDA::START));
    foreach ($acceptNodes as $acceptNode) {
      print "Adding transition from $acceptNode to ACCEPT STATE\n";
      $pda->addTransition($acceptNode, PDA::ACCEPT, PDA::ACCEPT);
    }

    return $pda;
  }

  private function walkProduction($production, $incomingNodes) {
    $pda = $this->pda;

    $outgoingNodes = array();
    foreach ($production->getTerms() as $term) { // parallel

      $currentNode = null;
      $prevNode = null;
      foreach ($term->getFactors() as $factor) { // series
        if (!$factor->isTerminal()) {
          print "++Walking production " . $factor->getID() . " with incoming\n";
          foreach (array( $prevNode ) as $incomingNode) {
            print "  $incomingNode\n";
          }
          $incomingNodes = $this->walkProduction($factor, array( $prevNode ));
          print "Got incoming nodes from " . $factor->getID() . " on production " . $production->getID() . " with prev node $prevNode:\n";
          foreach ($incomingNodes as $incomingNode) {
            print "  $incomingNode\n";
          }
        }
        else {
          $factorID = $factor->getID();
          $currentNode = $pda->createNode($factorID);

          if ($incomingNodes) {
            foreach ($incomingNodes as $incomingNode) {
              print "(CARRY) Adding transition from $incomingNode on $factorID to $currentNode\n";
              $pda->addTransition($incomingNode, $factorID, $currentNode);
            }
            $incomingNodes = array();
          }
          else {
            print "(NORML) Adding transition from $prevNode on $factorID to $currentNode\n";
            $pda->addTransition($prevNode, $factorID, $currentNode);
          }

          $prevNode = $currentNode;
        }
      }

      array_push($outgoingNodes, $prevNode);
    }

    print "Outgoing:\n";
    foreach ($outgoingNodes as $outgoingNode) {
      print "  $outgoingNode\n";
    }
    return $outgoingNodes;
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
