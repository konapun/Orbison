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
    $this->pda = new FluentPDA($pda);
    $this->currentNode = $node;
  }

  /*
   * Abstraction for a series of contiguous transitions
   */
  function series($units) {
    foreach ($units as $unit) {
      $this->one($unit);
    }
  }

  /*
   * Abstraction for:
   *  ( <rule> )
   */
  function zeroOrMore($unit) {
    // $this->pda->when($this->currentNode)->transition()
  }

  /*
   * Abstraction for:
   *  <rule> ( <rule> )
   */
  function oneOrMore($unit) {
    $this->pda->when($this->currentNode)->transition(array(
      $unit => $unit
    ));
  }

  /*
   * Abstraction for:
   *  <rule>
   */
  function one($unit) {
    $this->currentNode = $this->pda->when($this->currentNode)->transition(array( $unit => $unit))->getNodeID();
  }

  /*
   * Abstracton for:
   *    <thing1> <thing2> <thing3>
   *  | <thing1> <thing2>
   *  | <thing1>
   */
  function orSeries($units) {

  }

  /*
   * Abstraction for a list of alternatives:
   *  <thing1> | <thing2> | <thing3>
   */
  function orOneOf($choices) {
    $pda = $this->pda;
    $transitions = $fn();
    foreach ($choices as $choice) {

    }
  }
}
?>
