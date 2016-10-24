<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\Metasyntax\BNF\AST\Factor as Factor;

class StringNode extends Factor {
  private $value;

  function __construct($string) {
    $this->value = $string;
  }

  function getValue() {
    return $this->value;
  }
}

?>
