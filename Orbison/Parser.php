<?php
namespace Orbison;

use Orbison\Token as Token;
use Orbison\Parser\PDA as PDA;
use Orbison\Exception\ParseException as ParseException;
use Orbison\AST\Node as AST;

abstract class Parser {
  const EPSILON = '__epsilon__';

  /*
   * The rules method takes the start node of a state machine and sets up all
   * other nodes and transitions.
   */
  abstract protected function rules($pda, $ast);

  /*
   * Instantiate the PDA by calling the concrete parser to establish rules.
   * Then, put the token array through the machine and attempt to transition to
   * the ACCEPT state after all tokens are exhausted. The output of this method
   * is the AST for the input tokens which is built by establishing transition
   * callbacks within the PDA
   */
  final function parse($tokens) {
    $pda = new PDA();
    $ast = new AST(self::EPSILON);

    $this->handleErrors($pda, $tokens);
    $this->rules($pda, $ast); // call to abstract function from concrete implementor

    $pda->reset();
    foreach ($tokens as $token) {
      $pda->transition($token);
    }
    $pda->transition(new Token(PDA::ACCEPT, PDA::ACCEPT)); // must end in accept state in order to be a valid parse
    return $ast;
  }

  /*
   * Set up transition actions to throw informative errors
   */
  private function handleErrors($pda, $tokens) {
    $from = $tokens[0];
    $pda->onTransition(function($to) use (&$from) {
      $from = $to;
    });
    $pda->onTransition(PDA::FAIL, function($to) use (&$from) {
      if (!is_object($to)) {
        $to = new Token($to, '(internal)');
      }
      if (!is_object($from)) {
        $from = new Token($from, '(internal)');
      }

      throw new ParseException($from, $to);
    });
  }
}
?>
