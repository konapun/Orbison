<?php
namespace Orbison\Metasyntax\BNF\AST;

use Orbison\AST\Node as Node;
use Orbison\Metasyntax\BNF\AST\Term as Term;

class Expression extends Node {
  private $terms;

  function __construct($terms=array()) {
    $this->terms = $terms;
  }

  function addTerm(Term $term) {
    array_push($this->terms, $term);
  }

  function getTerms() {
    return $this->terms;
  }
}

?>
