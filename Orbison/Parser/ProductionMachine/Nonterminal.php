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

      if ($firstFactor->isTerminal()) {
        print $firstFactor->getID() . " is a terminal!\n";
        array_push($firstTerminals, $firstFactor);
      }
      else { // first factor is a production; need to recursively find first terminal
        print $firstFactor->getID() . " is a nonterminal; recursing!\n";
        $firstFactor->getFirstTerminals();
      }
    }

    return $firstTerminals;
  }

  final function isTerminal() {
    return false;
  }
}
?>
