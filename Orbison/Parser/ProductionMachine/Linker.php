<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\PDA as PDA;

class Placeholder {
  private $incoming;
  private $outgoing;

  function __construct($incoming=array(), $outgoing=array()) {

  }
}

/*
 * <phrase> ::= "hello" "," <target> <punctuation> "!"
 *              | "done";
 * <target> ::= "world"
 *            | [STRING];
 * <punctuation> ::= "!"
 *                 | "?"
 *                 | ".";
 */
class Linker {
  private $pda;
  private $linkedProductions;

  function __construct() {
    $this->pda = new PDA();
    $this->linkedProductions = array();
  }

  function link($production) {
    $pda = $this->pda;

    $acceptNodes = $this->linkProduction($production, PDA::START);
    foreach ($acceptNodes as $acceptNode) {
      print "Adding transition from $acceptNode on ACCEPT to ACCEPT\n";
      $pda->addTransition($acceptNode, PDA::ACCEPT, PDA::ACCEPT);
    }

    return $pda;
  }

  private function linkProduction($production, $incomingNodes, $factorIndex=0) {
    print "+-------------------------------------------------+\n";
    print "| Linking production " . $production->getID() . " |\n";
    print "+-------------------------------------------------+\n";
    if (!is_array($incomingNodes)) $incomingNodes = array($incomingNodes);

    $outgoingNodes = array();
    if ($this->haveSeenProduction($production, $factorIndex)) {
      return array();
      print "*** On SEEN production " . $production->getID() . " with incoming nodes " . $this->PRINT_ARRAY($incomingNodes) . "\n";
      return $incomingNodes; // FIXME
    }
    $this->markSeenProduction($production, $factorIndex);

    foreach ($production->getTerms() as $term) {
      $outgoingNodes = array_merge($outgoingNodes, $this->linkTerm($term, $incomingNodes));
    }
    return $outgoingNodes;
  }

  /*
   * Link individual rule alternatives for a production
   */
  private function linkTerm($term, $incomingNodes=array()) {
    print"Linking term " . $term->getID() . "\n";
    print "----------------------------------\n";
    $pda = $this->pda;

    $currentNode = null;
    $outgoingNodes = $incomingNodes;
    $factorIndex = 0;
    foreach ($term->getFactors() as $factor) {
      print "On factor " . $factor->getID() . " at index $factorIndex\n";
      if (!$factor->isTerminal()) { // factor is a production reference
        print "@@@ REFERENCE: " . $factor->getID() . " WITH INCOMING " . $this->PRINT_ARRAY($outgoingNodes) . "\n";
        $outgoingNodes = $this->linkProduction($factor, $outgoingNodes, $factorIndex);
        print "Got outgoing nodes " . $this->PRINT_ARRAY($outgoingNodes) . " from production ref " . $factor->getID() . "\n";
        $incomingNodes = array();
      }
      else {
        // print "On factor " . $factor->getID() . "\n";
        $factorID = $factor->getID();
        $factorNode = $pda->createNode($factorID);

        while ($incomingNode = array_pop($incomingNodes)) {
          print "!!! INCOMING Adding transition from $incomingNode on $factorID to $factorNode\n";
          $pda->addTransition($incomingNode, $factorID, $factorNode);
          // $incomingNode->addTransition($factorID, $factorNode); // TODO
        }

        if ($currentNode) {
          print "!!! Adding transition from $currentNode on $factorID to $factorNode\n";
          $pda->addTransition($currentNode, $factorID, $factorNode);
          // $currentNode->addTransition($factorID, $factorNode); // TODO - replace above once PDA refactor is in place
        }
        $currentNode = $factorNode;

        $outgoingNodes = array($currentNode);
      }
      $factorIndex++;
    }

    print "Returning from term " . $term->getID() . " with outgoing nodes " . $this->PRINT_ARRAY($outgoingNodes) . "\n";
    return $outgoingNodes;
  }


  private function haveSeenProduction($production, $index) {
    return array_key_exists($production->getID(), $this->linkedProductions) && $this->linkedProductions[$production->getID()] === $index;
  }

  private function markSeenProduction($production, $index) {
    $this->linkedProductions[$production->getID()] = $index;
  }

  private function PRINT_ARRAY($array) {
    if (!$array) return;
    return array_reduce($array, function($a, $b) { return "$a $b"; }, "");
  }
}
?>
