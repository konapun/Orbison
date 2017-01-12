<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\ProductionMachine\Term as Term;

/*
 * Abstraction for a single production
 */
class Production {
  private $machine;
  private $id;
  private $terms;

  function __construct($machine, $id) {
    $this->machine = $machine;
    $this->id = $id;
    $this->terms = array();
  }

  function addFactor($factor) {
    return $this->addTerm()->addFactor($factor);
  }

  function addTerm() {
    $term = new Term();
    array_push($this->terms, $term);

    return $term;
  }

  function getTerms() {
    return $this->terms;
  }

  function getID() {
    return $this->id;
  }

  function __toString() {
    return $this->getID(); // so productions can be used as factors without having to all $production->getID()
  }

}
?>
