<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Term as Term;

/*
 * Abstraction for a single production
 */
class Production {
  private $machine;
  private $terms;

  function __construct($machine) {
    $this->machine = $machine;
    $this->terms = array();
  }

  function addFactor($factor) {
    return $this->addTerm()->addFactor($factor);
  }

  function addTerm() {
    $term = new Term($this->machine);
    array_push($this->terms, $term);

    return $term;
  }

  function getTerms() {
    return $this->terms;
  }

  function getID() {

  }

  function __toString() {
    return $this->getID(); // so productions can be used as factors without having to all $production->getID()
  }
}
?>
