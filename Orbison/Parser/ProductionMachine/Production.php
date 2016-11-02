<?php
namespace Orbison\Parser\ProductionMachine;

use Orbison\Parser\FluentPDA as FluentPDA;

/*
 * Abstraction for a single production
 */
class Production {
  private $pda;
  private $currentNode;

  function __construct($pda, $node) {
    $this->pda = $pda;
    $this->fluentPDA = new FluentPDA($pda);
    $this->currentNode = $node;
  }

  /*
   * Abstraction for a series of contiguous transitions
   */
  function series($units) {
    foreach ($units as $unit) {
      $this->one($unit);
    }
    return $this;
  }

  /*
   * Abstraction for:
   *  <rule> ( <rule> )
   */
  function oneOrMore($unit) {
    $node = $this->one($unit);
    $this->fluentPda->when($node)->transition(array( $node => $node ));
    return $node;
  }

  /*
   * Abstraction for:
   *  <rule>
   */
  function one($unit) {
    $node = $this->pda->createNode($unit);
    $this->fluentPda->when($this->currentNode)->transition(array( $unit => $node ));
    $this->currentNode = $node;
    return $this;
  }

  /*
   * Abstracton for:
   *    <thing1> <thing2> <thing3>
   *  | <thing1> <thing2>
   *  | <thing1>
   */
  function orSeries($units) {
    foreach ($units as $series) {

    }
    return $this;
  }

  /*
   * Abstraction for a list of alternatives:
   *  <thing1> | <thing2> | <thing3>
   */
  function orOneOf($choices) {
    $pda = $this->pda;
    $currentNode = $this->currentNode;
    foreach ($choices as $choice) {
      $this->one($choice);
      $this->currentNode = $currentNode;
    }
    return $this;
  }
}
?>
