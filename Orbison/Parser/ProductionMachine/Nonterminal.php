<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Symbol as Symbol;

abstract class Nonterminal implements Symbol {

  final function isTerminal() {
    return false;
  }

}
?>
