<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\PDA as PDA;

/*
 * <phrase> ::= "hello" "," <target> <punctuation> <phrase>
 *            | "done" "!";
 * <target> ::= "world"
 *            | [STRING];
 * <punctuation> ::= "!"
 *                 | "."
 *                 | "?";
 */
class Linker {
  private $pda;

  function __construct() {
    $this->pda = new PDA();
  }

  function link($production) {
    $pda = $this->pda;

    $acceptNodes = $this->linkProduction($production, PDA::START);
    foreach ($acceptNodes as $acceptNode) {
      print "Adding ACCEPT transition from $acceptNode\n";
      $pda->addTransition($acceptNode, PDA::ACCEPT, PDA::ACCEPT);
      // $acceptNode->addTransition(PDA::ACCEPT, PDA::ACCEPT); // FIXME - change once new PDA impelmented
    }

    return $pda;
  }

  function linkProduction($production, $incoming, $recursingOnTerm=null) {
    if (!is_array($incoming)) $incoming = array($incoming);

    $outgoing = array();
    if ($recursingOnTerm) { // happens when a production reference is within the reference itself in a terminal position
      print "RECURSING! Production " . $production->getID() . " Term " . $recursingOnTerm->getID() . "!!!\n";
      // TODO: fix this
      $outgoing = array();
    }
    else {
      foreach ($production->getTerms() as $term) {
        $termOutgoing = $this->linkTerm($term, $incoming);

        $outgoing = array_merge($outgoing, $termOutgoing);
      }
    }

    return $outgoing;
  }

  private function linkTerm($term, $incoming) {
    if (!is_array($incoming)) $incoming = array($incoming);

    $outgoing = $incoming;
    $factors = $term->getFactors();
    $factorsCount = count($factors);
    foreach ($factors as $index => $factor) {
      if (!$factor->isTerminal()) {
        $recursingOn = ($index == $factorsCount-1) ? $term : null;
        $outgoing = $this->linkProduction($factor, $outgoing, $recursingOn);
      }
      else {
        $outgoing = $this->linkFactor($factor, $outgoing);
      }
    }

    return $outgoing;
  }

  private function linkFactor($factor, $incoming) {
    if (!is_array($incoming)) $incoming = array($incoming);

    $pda = $this->pda;
    $factorID = $factor->getID();
    $factorNode = $pda->createNode($factorID);
    while ($incomingNode = array_pop($incoming)) {
      print "Adding FACTOR transition from $incomingNode on $factorID to $factorNode\n";
      $pda->addTransition($incomingNode, $factorID, $factorNode);
      // $incomingNode->addTransition($factorID, $factorNode); // TODO - change once new PDA is implemented
    }

    return array($factorNode);
  }
}
?>
