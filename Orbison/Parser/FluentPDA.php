<?php
namespace Orbison\Parser;

use Orbison\Parser\PDA as PDA;

class FluentPDA {
  private $pda;

  function __construct(PDA $pda) {
    $this->pda = $pda;
  }

  function when($nodeID) {
    return new FluentNode($this->pda, $nodeID);
  }

  /*
   * Get the PDA built by this fluent interface
   */
  function exportPDA() {
    return $this->pda;
  }
}

class FluentNode {
  private $pda;
  private $nodeID;

  function __construct($pda, $nodeID) {
    $this->pda = $pda;
    $this->nodeID = $nodeID;
  }

  /*
   * Map node encounters to transitions
   */
  function transition($map) {
    $pda = $this->pda;
    $nodeID = $this->nodeID;
    $edges = array();
    foreach ($map as $token => $match) {
      array_push($edges, $token);
      $pda->addTransition($nodeID, $token, $match);
    }

    return new FluentTransition($pda, $nodeID, $edges);
  }
}

class FluentTransition {
  private $pda;
  private $nodeID;
  private $edges;

  function __construct($pda, $nodeID, $edges) {
    $this->pda = $pda;
    $this->nodeID = $nodeID;
    $this->edges = $edges;
  }

  function with($event) {
    $pda = $this->pda;
    $nodeID = $this->nodeID;
    foreach ($this->edges as $edge) {
      $pda->onTransitionFrom($nodeID, $edge, $event);
    }
    return $this;
  }

  function terminal() {
    $this->pda->addTransition($this->nodeID, PDA::ACCEPT, PDA::ACCEPT);
    return $this;
  }
}
?>
