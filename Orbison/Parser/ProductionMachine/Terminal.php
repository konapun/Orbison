<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Symbol as Symbol;

abstract class Terminal implements Symbol {

  function getFirstTerminals() {
    return array($this);
  }

  final function isTerminal() {
    return true;
  }

}
?>
