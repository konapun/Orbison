<?php
namespace Orbison\Parser;

use Orbison\Parser\StateNode as Node;
use Orbison\Exception\StateException as StateException;

/*
 * A pushdown automaton
 *
 * This PDA dynamically builds its alphabet based on transitions such that every
 * transition to or from a nonexistant node creates the missing nodes. The stack
 * is only used for stackMatches while every other transition is the same as a
 * nondeterministic finite automaton.
 *
 * A failed transition will automatically go to the FAIL state which must be
 * caught by registering an onTransition callback.
 *
 * ex:
 * 	$pda->onTransition(PDA::FAIL, function() {
 * 		throw new StateException();
 * 	});
 */
class PDA {

  const START = '__start__';
  const ACCEPT = '__accept__';
  const FAIL = '__fail__';

  private $state;
  private $nodes;
  private $stack;
  private $events;
  private $eventsFrom;

  function __construct() {
    $this->nodes = array();
    $this->stack = array();
    $this->events = array(
      '__all__' => array()
    );
    $this->eventsFrom = array();

    // Initial nodes
    $start = $this->getOrCreateNode(self::START);
    $accept = $this->getOrCreateNode(self::ACCEPT);
    $fail = $this->getOrCreateNode(self::FAIL);

    // Initial transitions
    $this->addTransition($start, self::FAIL, $fail);
    $this->addTransition($accept, self::ACCEPT, $accept);

    $this->state = $start;
  }

  function getStackCount() {
    return count($this->stack);
  }

  function pushStack($item) {
    array_push($this->stack, $item);
  }

  function popStack() {
    return array_pop($this->stack);
  }

  /*
   * Get the current state of the PDA
   */
  function getState() {
    return $this->state->getID();
  }

  /*
   * Internal state nodes shouldn't be exposed since transitions could be added
   * which the PDA doesn't know about. Rather than returning the node directly,
   * `createNode` returns an ID which can be passed to the PDA to work with but
   * is otherwise unusable to the client
   */
  function createNode($id) {
    return $this->createActualNode($id)->getID();
  }

  /*
   * Fluent interface for building the network
   */
  function when($nodeID) {
    return new FluentNode($this, $nodeID);
  }

  function addTransition($node1, $edge, $node2) {
    $node1 = $this->getOrCreateNode($node1);
    $node2 = $this->getOrCreateNode($node2);

    $node1->addTransition($edge, $node2);
  }

  /*
   * Set a function to run when a transition is made from node $from following
   * edge $edge
   */
  function onTransitionFrom($from, $edge, $fn) {
    $this->getEventsFrom($from, $edge);
    array_push($this->eventsFrom[$from][$edge], $fn);
  }

  /*
   * Set a function to run when triggered by a transition to the node(s)
   * specified by $id, which may be an array. If no IDs are given, set the
   * callback to be invoked every transition.
   */
  function onTransition($id, $fn=null) {
    if (is_null($fn)) {
      $fn = $id;
      $id = '__all__';
    }
    else if (!is_array($id)) {
      $id = array($id);
    }

    if (is_array($id)) {
      foreach ($id as $nodeID) {
        $this->onSingleTransition($nodeID, $fn);
      }
    }
    else {
      $this->onSingleTransition($id, $fn);
    }
  }

  /*
   * When the node with ID $id1 is seen, pop the stack and expect $id2
   */
  function stackMatch($id1, $id2) {
    $node1 = $this->getOrCreateNode($id1);
    $node2 = $this->getOrCreateNode($id2);

    $that = $this;
    $this->onTransition($id1, function() use ($that, $id2) {
      $symbol = $that->popStack();
      if ($symbol != $id2) {
        $that->addTransition($that->getState(), self::FAIL, self::FAIL);
        $that->transition(self::FAIL);
      }
    });
    $this->onTransition($id2, function() use ($that, $id2) {
      $that->pushStack($id2);
    });
  }

  /*
   * Require that the stack be empty at a given point in time and transition to
   * the fail state if it's not
   */
  function requireEmptyStack() {
    if (!empty($this->stack)) {
      $this->addTransition($this->getState(), self::FAIL, self::FAIL);
      $this->transition(self::FAIL);
    }
  }

  function reset() {
    $this->state = $this->getOrCreateNode(self::START);
    $this->stack = array();
  }

  /*
   * The transition function attempts to make a transition from the current node
   * to the node specified by $id. If the transition doesn't exist for any
   * reason, including no such node in the network, an automatic transition to
   * the FAIL state is made, for which there are no other available transitions.
   *
   * Use transition events to catch error state.
   */
  function transition($id) {
    $node = $id;
    if (is_object($id)) { // transitioning from a NodeAdapter
      $id = $node->getID();
    }

    $prev = $this->state;
    $transitionNode = $this->state->transition($id);
    if (array_key_exists($id, $this->nodes) && !is_null($transitionNode)) {
      $this->state = $transitionNode;
    }
    else {
      $this->state = $this->nodes[self::FAIL];
    }

    $to = $this->state->getID();

    // Run onTransitionFrom events
    foreach ($this->getEventsFrom($prev->getID(), $id) as $event) {
      $event($to, $prev);
    }

    // Run onTransition events
    foreach (array_merge($this->events[$to], $this->events['__all__']) as $event) {
      $event($to, $prev);
    }
    return $to;
  }

  /*
   * Get the events set when transitioning from a node on a transition, building
   * the datastructure if it does not exist
   */
  private function getEventsFrom($node, $transition=null) {
    if (!array_key_exists($node, $this->eventsFrom)) {
      $this->eventsFrom[$node] = array();
    }
    if (is_null($transition)) {
      return $this->eventsFrom[$node];
    }

    if (!array_key_exists($transition, $this->eventsFrom[$node])) {
      $this->eventsFrom[$node][$transition] = array();
    }

    return $this->eventsFrom[$node][$transition];
  }

  /*
   * Set a function to run when triggered by a transition to the node with id
   * $id.
   */
  private function onSingleTransition($id, $fn=null) {
    $node = $this->getOrCreateNode($id);
    array_push($this->events[$id], $fn);
  }

  private function hasNode($id) {
    return array_key_exists($id, $this->nodes);
  }

  /*
   * Return the node specified by the given ID if it exists. Else, create it,
   * add it to the network, add an event slot for it, and return it.
   */
  private function getOrCreateNode($id) {
    if (is_object($id)) {
      $id = $id->getID();
    }
    if ($this->hasNode($id)) {
      return $this->nodes[$id];
    }

    return $this->createActualNode($id);
  }

  private function createActualNode($id) {
    $val = $id;
    if ($this->hasNode($id)) {
      $incr = 1;
      while ($this->hasNode("$id-$incr")) $incr++;
      $id = "$id-$incr";
    }

    $node = new Node($id, $val);
    $this->nodes[$id] = $node;
    $this->events[$id] = array();
    return $node;
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
