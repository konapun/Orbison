<?php
namespace Orbison\Parser\PDA;

use Orbison\Parser\PDA\Node as Node;

class Automaton {
  // Build-in nodes
  const START = '__start__';
  const ACCEPT = '__accept__';
  const FAIL = '__fail__';

  private $nodes;
  private $state;
  private $stack;

  function __construct() {
    $this->nodes = array();
    $this->stack = array();

    $startNode = $this->createNode(self::START);
    $acceptNode = $this->createNode(self::ACCEPT);
    $failNode = $this->createNode(self::FAIL);

    $startNode->addTransition(self::FAIL, $failNode);
    $acceptNode->addTransition(self::ACCEPT, $acceptNode);

    $this->state = $startNode;
  }

  function createNode($id) {
    if ($this->hasNode($id)) {
      // TODO
    }

    $node = new Node($id, $this);
    $this->nodes[$id] = $node;
    return $node;
  }

  function getStackCount() {
    return count($this->stack);
  }

  function pushStack($item) {
    array_push($this->stack, $item);
  }

  function getState() {
    return $this->state->getID();
  }


  private function hasNode($id) {
    return array_key_exists($id, $this->nodes);
  }
}
?>
