<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\Metasyntax\BNF\AST\Factor as Factor;

class Token extends Factor {
  private $value;
  private $type;

  function __construct($value, $type) {
    $this->value = $value;
    $this->type = $type;
  }

  function getValue() {
    return $this->value;
  }

  function getType() {
    return $this->type;
  }
}

?>
