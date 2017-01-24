<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Symbol as Symbol;

/*
 * Factors are automatically created by factors from input strings and are the
 * atomic units of a grammar. You should not have to use this class.
 */
class Factor extends Symbol {
  private $id;

  function __construct($id) {
    $this->id = $id;
  }

  function getID() {
    return $this->id;
  }

  function isTerminal() {
    return true;
  }

}
?>
