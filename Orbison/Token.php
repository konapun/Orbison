<?php
namespace Orbison;

/*
 * Tokens are created by the lexer as output of the transformation of the source
 * string
 */
class Token {
  private $type;
  private $value;
  private $line;
  private $column;

  function __construct($value, $type, $line=-1, $column=-1) {
    $this->type = $type;
    $this->value = $value;
    $this->line = $line;
    $this->column = $column;
  }

  function getID() {
    return $this->getType();
  }

  function getType() {
    return $this->type;
  }

  function getValue() {
    return $this->value;
  }

  function getLine() {
    return $this->line;
  }

  function getColumn() {
    return $this->column;
  }

  function __toString() {
    return $this->value;
  }
}
?>
