<?php

use Orbison\Parser\PDA as PDA;

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

  function getNodeID() {
    return $this->nodeID;
  }

  function terminal() {
    $this->pda->addTransition($this->nodeID, PDA::ACCEPT, PDA::ACCEPT);
    return $this;
  }
}
?>
