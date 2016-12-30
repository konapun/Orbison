<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Term as Term;

/*
 * Abstraction for a single production
 */
class Production {
  private $terms;

  function __construct() {
    $this->terms = array();
  }

  function addFactor($factor) {
    $term = $this->addTerm();
    return $term->addFactor($factor);
  }

  function addTerm() {
    $term = new Term();
    array_push($this->terms, $term);

    return $term;
  }

  function getID() {

  }
}
?>
