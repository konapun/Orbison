<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Parser as Parser;
use Orbison\Parser\PDA as PDA;
use Orbison\Metasyntax\BNF\BNFToken as Token;
use Orbison\Metasyntax\BNF\AST\Grammar as Grammar;
use Orbison\Metasyntax\BNF\AST\Production as Production;
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
    $beginRepeat = $pda->createNode(Token::BEGIN_REPEAT);
    $endRepeat = $pda->createNode(Token::END_REPEAT);

    // Define transtions
$test = array(); // test nodes
    $pda->when(PDA::START)->transition(array(
      Token::RULE  => $lhsRule
    ))->with(function($to, $from) use ($ast, $test) {
      echo "START: Transitioning from $from to $to\n";
      $child = 'grammar';
      //echo "\tAdding child $child to empty array\n";
      array_push($test, $child);
    });

    $pda->when($lhsRule)->transition(array(
      Token::ASSIGNMENT => $assignment
    ))->with(function($to, $from) use (&$test) {
      echo "1. Transitioned from $from to $to\n";
      $child = 'production';
      //echo "\tAdding child '$child' to node '" . end($test) . "'\n";
      array_push($test, $child);
    });

    // Define an AST node with left child as the class and the right child as the rule
    $pda->when($assignment)->transition(array(
      Token::STRING       => $string,
      Token::RULE         => $rhsRule,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) use (&$test) {
      echo "2. Transitioned from $from to $to\n";
      $child = $to;
      //echo "\tAdding child '$child' to node '" . end($test) . "'\n";
    });

    $pda->when($string)->transition(array(
      Token::STRING       => $string,
      Token::SEMICOLON    => $semicolon,
      Token::RULE         => $rhsRule,
      Token::PIPE         => $pipe,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat,
      Token::END_REPEAT   => $endRepeat
    ))->with(function($to, $from) use (&$test) {
      echo "3. Transitioned from $from to $to\n";
      $child = "";
      switch ($to)  {
        case Token::PIPE:
          break;
        case Token::SEMICOLON:

          break;
        default:
          $child = $to;
      }
      $child = $to;
      //echo "\tAdding child '$child' to node '" . end($test) . "'\n";
    });

    $pda->when($pipe)->transition(array(
      Token::STRING       => $string,
      Token::RULE         => $rhsRule,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) {
      echo "5. Transitioned from $from to $to\n";
    });

    $pda->when($rhsRule)->transition(array(
      Token::STRING       => $string,
      Token::PIPE         => $pipe,
      Token::RULE         => $rhsRule,
      Token::SEMICOLON    => $semicolon,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat,
      Token::END_REPEAT   => $endRepeat
    ))->with(function($to, $from) {
      echo "6. Transitioned from $from to $to\n";
    });

    $pda->when($token)->transition(array(
      Token::STRING     => $string,
      Token::PIPE       => $pipe,
      Token::RULE       => $rhsRule,
      Token::SEMICOLON  => $semicolon,
      Token::TOKEN      => $token,
      Token::END_REPEAT => $endRepeat
    ))->with(function($to, $from) {
      echo "7: Transitioned from $from to $to\n";
    });

    $pda->when($action)->transition(array(
      Token::SEMICOLON => $semicolon
    ));

    $pda->when($beginRepeat)->transition(array(
      Token::STRING => $string,
      Token::PIPE   => $pipe,
      Token::RULE   => $rhsRule,
      Token::TOKEN  => $token
    ))->with(function($to, $from) {
      echo "8: Transitioned from $from to $to\n";
    });

    $pda->when($endRepeat)->transition(array(
      Token::STRING       => $string,
      Token::PIPE         => $pipe,
      Token::RULE         => $rhsRule,
      Token::TOKEN        => $token,
      Token::SEMICOLON    => $semicolon,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) {
      echo "9: Transitioned from $from to $to\n";
    });

    $pda->when($semicolon)->transition(array(
      Token::RULE  => $lhsRule
    ))->terminal()->with(function($to, $from) use ($pda) {
      $pda->requireEmptyStack(); // any END_REPEAT token must occur within the same rule
      echo "4. Transitioned from $from to $to\n";
    });

    $pda->stackMatch(Token::END_REPEAT, Token::BEGIN_REPEAT);

    $pda->onTransition(function($node) {
      echo "-------------Transitioning-------------\n";
      //echo "Transitioning to $node (" . $node->getID() . ")\n";
    });
  }
}
?>
