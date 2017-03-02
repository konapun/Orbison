<?php
namespace Orbison\Parser\PDA;

class Node {
  private $id;
  private $pda;
  private $transitions;
  private $transitionFromEvents;
  private $transitionToEvents;

  function __construct($id, $pda) {
    $this->id = $id;
    $this->pda = $pda;
    $this->transitions = array();
    $this->transitionFromEvents = array();
    $this->transitionToEvents = array();
  }

  function getID() {
    return $this->id;
  }

  function onTransitionTo($fn) {
    array_push($this->transitionToEvents, $fn);
  }

  function onTransitionFrom($fn) {
    array_push($this->transitionFromEvents, $fn);
  }

  function addTransition($edge, $node) {
    if ($this->hasTransition($edge)) {
      throw new TransitionException($node, $edge, "Transition already set for (node, edge) ($node, $edge)");
    }

    $this->transitions[$edge] = $node;
  }

  function transition($edge) {
    if ($this->hasTransition($edge)) {
      $nextNode = $this->transitions[$edge];

      foreach ($this->transitionFromEvents as $fn) {
        $fn($edge, $nextNode);
      }
      foreach ($nextNode->transitionToEvents as $fn) {
        $fn($edge, $this);
      }

      return $nextNode;
    }
    return null;
  }

  function __toString() {
    return $this->getID();
  }

  private function hasTransition($edge) {
    return array_key_exists($edge, $this->transitions);
  }
}
?>
