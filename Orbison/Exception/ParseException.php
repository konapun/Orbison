<?php
namespace Orbison\Exception;

use Orbison\Token as Token;

/*
 * A parse exception is thrown by the parser and is caused by a bad transition
 * attempt from one token to another
 */
class ParseException extends \Exception {
  private $from;
  private $to;

  function __construct(Token $from, Token $to) {
    $this->from = $from;
    $this->to = $to;

    parent::__construct("Parse error transitioning from '$from' of type " . $from ->getType() . " to '$to' of type " . $to->getType() . " at [line: " . $to->getLine() . "; column: " . $to->getColumn() . "]");
  }

  function getFromToken() {
    return $this->from;
  }

  function getToToken() {
    return $this->to;
  }

  function getColumn() {
    return $this->to->getColumn();
  }
}
?>
