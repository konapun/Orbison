<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\AST\Node as Node;
use Orbison\Metasyntax\BNF\AST\Factor as Factor;

class Term extends Node {
  private $factors;

  function __construct($factors=array()) {
    $this->factors = $factors;
  }

  function addFactor(Factor $factor) {
    array_push($this->factors, $factor);
  }
}

?>
