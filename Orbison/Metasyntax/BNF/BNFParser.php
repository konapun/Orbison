<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Parser as Parser;
use Orbison\Parser\PDA as PDA;
use Orbison\Metasyntax\BNF\BNFToken as Token;
use Orbison\AST\NodeFactory as Factory;

class BNFParser extends Parser {

  /*
   * Define rules in terms of state machine transitions. Subsequent parsers will
   * be defined using BNF.
   */
  protected function rules($pda, $ast) {

    // Define PDA nodes
    $lhsRule = $pda->createNode(Token::RULE);
    $rhsRule = $pda->createNode(Token::RULE);
    $assignment = $pda->createNode(Token::ASSIGNMENT);
    $string = $pda->createNode(Token::STRING);
    $pipe = $pda->createNode(Token::PIPE);
    $semicolon = $pda->createNode(Token::SEMICOLON);
    $token = $pda->createNode(Token::TOKEN);
    $action = $pda->createNode(Token::ACTION);

    // Define transtions

    $pda->when(PDA::START)->transition(array(
      Token::RULE  => $lhsRule
    ))->with(function($to, $from) {
      echo "START: Transitioning from $from to $to\n";
    });

    $pda->when($lhsRule)->transition(array(
      Token::ASSIGNMENT => $assignment
    ))->with(function($to, $from) {
      echo "1. Transitioned from $from to $to\n";
    });

    // Define an AST node with left child as the class and the right child as the rule
    $pda->when($assignment)->transition(array(
      Token::STRING => $string,
      Token::RULE   => $rhsRule,
      Token::TOKEN  => $token
    ))->with(function($to, $from) {
      echo "2. Transitioned from $from to $to\n";
    });

    $pda->when($string)->transition(array(
      Token::STRING    => $string,
      Token::SEMICOLON => $semicolon,
      Token::RULE      => $rhsRule,
      Token::PIPE      => $pipe,
      Token::TOKEN     => $token
    ))->with(function($to, $from) {
      echo "3. Transitioned from $from to $to\n";
    });

    $pda->when($pipe)->transition(array(
      Token::STRING => $string,
      Token::RULE   => $rhsRule,
      Token::TOKEN  => $token
    ))->with(function($to, $from) {
      echo "5. Transitioned from $from to $to\n";
    });

    $pda->when($rhsRule)->transition(array(
      Token::STRING    => $string,
      Token::PIPE      => $pipe,
      Token::RULE      => $rhsRule,
      Token::SEMICOLON => $semicolon,
      Token::TOKEN     => $token
    ))->with(function($to, $from) {
      echo "6. Transitioned from $from to $to\n";
    });

    $pda->when($token)->transition(array(
      Token::STRING    => $string,
      Token::PIPE      => $pipe,
      Token::RULE      => $rhsRule,
      Token::SEMICOLON => $semicolon,
      Token::TOKEN     => $token
    ))->with(function($to, $from) {
      echo "7: Transitioned from $from to $to\n";
    });

    $pda->when($action)->transition(array(
      Token::SEMICOLON => $semicolon
    ));

    $pda->when($semicolon)->transition(array(
      Token::RULE  => $lhsRule
    ))->terminal()->with(function($to, $from) {
      echo "4. Transitioned from $from to $to\n";
    });

    $pda->onTransition(function($node) {
      echo "-------------Transitioning-------------\n";
      //echo "Transitioning to $node (" . $node->getID() . ")\n";
    });
  }
}
?>
