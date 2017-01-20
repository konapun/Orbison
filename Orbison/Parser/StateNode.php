<?php
namespace Orbison\Parser;

use Orbison\Exception\TransitionException as TransitionException;

/*
 * Nodes are used internally by the state machine. All user interactions with
 * the state network are handled by the state machine using node IDs, not nodes
 * themselves.
 */
 class StateNode {
   private $id;
   private $value;
   private $transitions;

   function __construct($id, $value=null) {
     if (is_null($value)) $value = $id;

     $this->id = $id;
     $this->value = $value;
     $this->transitions = array(); //
   }

   function getID() {
     return $this->id;
   }

   function getValue() {
     return $this->value;
   }

   /*
    * Specify the node to transition to when $edge is seen
    */
   function addTransition($edge, $node) {
     if (isset($this->transitions[$edge])) {
       throw new TransitionException($node, $edge, "Transition already set for (node, edge) ($node, $edge)");
     }
     $this->transitions[$edge] = $node;
   }

   function transition($edge) {
     if (array_key_exists($edge, $this->transitions)) {
       return $this->transitions[$edge];
     }
     return null;
   }

   function __toString() {
     return $this->value;
   }
 }
?>
