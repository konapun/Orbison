<?php
namespace Orbison\AST;

use \ReflectionClass as ReflectionClass;

/*
 * Allows building objects whose classes use constructor-based DI piece by piece
 * for instances where arguments aren't known ahead of time
 */
class Builder {
  private $class;
  private $args;

  function __construct($class) {
    $this->class = $class;
    $this->args = array();
  }

  function setClass($class) {
    $this->class = $class;
  }

  function pushArgument($arg) {
    array_push($this->args, $arg);
  }

  function popArgument() {
    return array_pop($this->args);
  }

  function unshiftArgument($arg) {
    array_unshift($this->args, $arg);
  }

  function shiftArgument() {
    return array_shift($this->args);
  }

  function getArgumentCount() {
    return count($this->args);
  }

  function hasArguments() {
    return $this->getArgumentCount() > 0;
  }

  function clear() {
    $this->args = array();
  }

  function build($class="") {
    if ($class) {
      $this->setClass($class);
    }

    $reflection = new ReflectionClass($this->class);
    $built = $reflection->newInstanceArgs($this->args);
    $this->clear();
    return $built;
  }
}
?>
