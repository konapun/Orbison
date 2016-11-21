<?php

use Orbison\Parser\PDA as PDA;

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
?>
