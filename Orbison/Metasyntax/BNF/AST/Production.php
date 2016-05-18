<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\AST\Node as Node;
use Orbison\Metasyntax\BNF\AST\Expression as Expression;

class Production extends Node {
  private $identifier;
  private $expresion;

  function __construct($identifier, $expression) {
    $this->identifier = $identifier;
    $this->expression = $expression;
  }

  function getIdentifier() {
    return $this->identifier;
  }

  function getExpression() {
    return $this->expression;
  }
}

?>
