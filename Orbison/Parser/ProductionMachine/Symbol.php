<?php
namespace Orbison\Parser\ProductionMachine;

abstract class Symbol {

  /*
   * Return a unique identifier for the symbol
   */
  abstract function getID();

  /*
   * Returns whether or not this unit is a terminal symbol
   */
  abstract function isTerminal();

  function __toString() {
    return $this->getID();
  }
}
?>
