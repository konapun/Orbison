<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Terminal as Terminal;

/*
 * Factors are automatically created by factors from input strings and are the
 * atomic units of a grammar. You should not have to use this class.
 */
class Factor extends Terminal {
  private $id;

  function __construct($id) {
    $this->id = $id;
  }

  function getID() {
    return $this->id;
  }

}
?>
