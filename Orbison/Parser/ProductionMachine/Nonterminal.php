<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Symbol as Symbol;

abstract class Nonterminal implements Symbol {

  /*
   * Template method for setting the collection which this abstract class
   * traverses in order to perform its terminal finding
   */
  protected abstract function getCollection();

  /*
   * Traverse the rules, returning all first encountered terminal symbols
   */
  function getFirstTerminals() {
    $firstTerminals = array();

    $collection = $this->getCollection();
    if (count($collection) > 0) {
      $firstFactor = $collection[0];
      print $this->getID() . " is NOT a terminal; recursing\n";
      $firstTerminals = array_merge($firstTerminals, $firstFactor->getFirstTerminals());
    }

    return $firstTerminals;
  }

  final function isTerminal() {
    return false;
  }
}
?>
