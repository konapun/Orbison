<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\AST\Node as Node;
use Orbison\Metasyntax\BNF\AST\Production as Production;

/*
 * Root node of the AST
 */
class Grammar extends Node {
  private $productions;

  function __construct($productions=array()) {
    $this->productions = $productions;
  }

  function addProduction(Production $production) {
    array_push($this->productions, $production);
  }

  function getProductions() {
    return $this->productions;
  }
}
?>
