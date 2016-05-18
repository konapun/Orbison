<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\Metasyntax\BNF\AST\Factor as Factor;

class Identifier extends Factor {
  private $name;

  function __construct($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }
}

?>
