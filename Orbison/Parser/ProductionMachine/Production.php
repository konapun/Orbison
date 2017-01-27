<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\Productionmachine\Nonterminal as Nonterminal;
use Orbison\Parser\ProductionMachine\Term as Term;

/*
 * Abstraction for a single production
 */
class Production extends Nonterminal {
  private $ID_PREFIX = '__PRODMACHINE__PROD__';
  private $TERM_PREFIX = '__PRODMACHINE__TERM__';

  private $termBase;
  private $machine;
  private $id;
  private $terms;

  function __construct($machine, $id) {
    $this->machine = $machine;
    $this->id = $this->ID_PREFIX . $id;
    $this->termBase = $this->TERM_PREFIX . $id;
    $this->terms = array();
  }

  function addFactor($factor) {
    return $this->addTerm()->addFactor($factor);
  }

  function addTerm() {
    $termID = $this->termBase . count($this->terms);
    $term = new Term($this, $termID);
    array_push($this->terms, $term);

    return $term;
  }

  function getTerms() {
    return $this->terms;
  }

  function getID() {
    return $this->id;
  }

  function isProduction($id) {
    $prefix = $this->ID_PREFIX;
    return substr($id, 0, strlen($prefix)) === $prefix;
  }

  function getFirstTerminals() {
    $firstTerminals = array();
    foreach ($this->getTerms() as $term) {
      $firstTerminals = array_merge($firstTerminals, $term->getFirstTerminals());
    }

    return $firstTerminals;
  }

}
?>
