<?php
namespace Orbison\Exception;

class TransitionException extends \LogicException {
  private $node;
  private $transition;

  function __construct($node, $transition, $message) {
    $this->node = $node;
    $this->transition = $transition;
    
    parent::__construct($message);
  }

  function getNode() {
    return $this->node;
  }

  function getTransition() {
    return $this->transition;
  }
}
?>
