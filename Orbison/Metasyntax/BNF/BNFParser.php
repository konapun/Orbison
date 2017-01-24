<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Parser as Parser;
use Orbison\Parser\PDA as PDA; // TODO: remove once production machine is working
use Orbison\Parser\FluentPDA as FluentPDA;
use Orbison\Parser\ProductionMachine as ProductionMachine;
use Orbison\Metasyntax\BNF\BNFToken as Token;
use Orbison\Metasyntax\BNF\AST\Grammar as Grammar;
use Orbison\Metasyntax\BNF\AST\Production as Production;
use Orbison\AST\Builder as NodeBuilder;

use Orbison\AST\Node as TestNode; // FIXME

class BNFParser extends Parser {

  /*
   * Define rules in terms of production machine transitions on a PDA
   */
  protected function rules() {
    $productionMachine = new ProductionMachine();

    // All productions in the language
    $grammar = $productionMachine->createProduction('grammar');
    $production = $productionMachine->createProduction('production');
    $expression = $productionMachine->createProduction('expression');
    $term = $productionMachine->createProduction('term');
    $factor = $productionMachine->createProduction('factor');

    // <grammar> ::= <production> ( <production> );
    $grammar->addTerm()->addFactor(array( $production, $grammar ));
    // TODO - add transition to accept state

    // <production> ::= [IDENTIFIER] "::=" <expression> ";";
    $production->addFactor(array( Token::IDENTIFIER, '::=', $expression, ';' ));

    // <expression> ::= <term> ( "|" <term> );
    $expression->addTerm()->addFactor($term);
    $expression->addTerm()->addFactor(array( Token::PIPE, $term ));

    // <term> ::= <factor> ( <factor> );
    // $term->addTerm()->addFactor($factor); // FIXME
    $term->addTerm()->addFactor(array( $factor, $term ));

    /*
     * <factor>     ::= '"' [STRING] '"'
     *                | "'" [STRING] "'"
     *                | "<" [IDENTIFIER] ">"
     *                | "[" [TOKEN] "]"
     *                | "(" <expression> ")"
     *              ;
     */
    $factor->addFactor(Token::STRING);
    $factor->addFactor(Token::IDENTIFIER);
    $factor->addFactor(Token::TOKEN);

    $pda = $productionMachine->exportPDA();
    $pda->stackMatch(Token::END_REPEAT, Token::BEGIN_REPEAT);
    // $pda->onTransition(array($pipe, $semicolon), function() use ($pda) {
      // $pda->requireEmptyStack(); // any END_REPEAT token must occur within the same rule
    // });

    /* DEBUG */
    $pda->onTransition(function($node, $prev, $token) {
      echo "------------- Transitioning -------------\n";
      //echo "Transitioning to $node (" . $node->getID() . ")\n";
    });

    return $pda;
  }

  /*
   * Define rules in terms of state machine transitions. Subsequent parsers will
   * be defined using BNF.
   *
   * TODO: remove once `rules` is working
   */
  protected function rules2() {
    $pda = new PDA();

    /* Define PDA nodes */
    $lhsRule = $pda->createNode(Token::IDENTIFIER); // The production identifier
    $rhsRule = $pda->createNode(Token::IDENTIFIER); // Reference to a production
    $assignment = $pda->createNode(Token::ASSIGNMENT); // ::=
    $string = $pda->createNode(Token::STRING); // "string"
    $pipe = $pda->createNode(Token::PIPE); // |
    $semicolon = $pda->createNode(Token::SEMICOLON); // ;
    $token = $pda->createNode(Token::TOKEN); // [TOKEN]
    $action = $pda->createNode(Token::ACTION); // { action... }
    $beginRepeat = $pda->createNode(Token::BEGIN_REPEAT); // (
    $endRepeat = $pda->createNode(Token::END_REPEAT); // )

    /* AST root */
    $ast = new TestNode();

    /* Define transtions */
    $fluentPda = new FluentPDA($pda);

    /* Production 1 */
    $fluentPda->when(PDA::START)->transition(array(
      Token::IDENTIFIER => $lhsRule
    ))->with(function($to, $from) {
      echo "START: Transitioning from $from to $to\n";
    });

    /* Production 2 */
    $fluentPda->when($lhsRule)->transition(array(
      Token::ASSIGNMENT => $assignment
    ))->with(function($to, $from) {
      echo "1. Transitioned from $from to $to\n";
    });
    $fluentPda->when($assignment)->transition(array(
      Token::STRING       => $string,
      Token::IDENTIFIER   => $rhsRule,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) {
      echo "2. Transitioned from $from to $to\n";
    });

    $fluentPda->when($string)->transition(array(
      Token::STRING       => $string,
      Token::SEMICOLON    => $semicolon,
      Token::IDENTIFIER   => $rhsRule,
      Token::PIPE         => $pipe,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat,
      Token::END_REPEAT   => $endRepeat
    ))->with(function($to, $from) {
      echo "3. Transitioned from $from to $to\n";
    });

    $fluentPda->when($pipe)->transition(array(
      Token::STRING       => $string,
      Token::IDENTIFIER   => $rhsRule,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) {
      echo "5. Transitioned from $from to $to\n";
    });

    $fluentPda->when($rhsRule)->transition(array(
      Token::STRING       => $string,
      Token::PIPE         => $pipe,
      Token::IDENTIFIER   => $rhsRule,
      Token::SEMICOLON    => $semicolon,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat,
      Token::END_REPEAT   => $endRepeat
    ))->with(function($to, $from) {
      echo "6. Transitioned from $from to $to\n";
    });

    $fluentPda->when($token)->transition(array(
      Token::STRING       => $string,
      Token::PIPE         => $pipe,
      Token::IDENTIFIER   => $rhsRule,
      Token::SEMICOLON    => $semicolon,
      Token::TOKEN        => $token,
      Token::BEGIN_REPEAT => $beginRepeat,
      Token::END_REPEAT   => $endRepeat
    ))->with(function($to, $from) {
      echo "7: Transitioned from $from to $to\n";
    });

    $fluentPda->when($action)->transition(array(
      Token::SEMICOLON => $semicolon
    ));

    $fluentPda->when($beginRepeat)->transition(array(
      Token::STRING     => $string,
      Token::PIPE       => $pipe,
      Token::IDENTIFIER => $rhsRule,
      Token::TOKEN      => $token
    ))->with(function($to, $from) {
      echo "8: Transitioned from $from to $to\n";
    });

    $fluentPda->when($endRepeat)->transition(array(
      Token::STRING       => $string,
      Token::PIPE         => $pipe,
      Token::IDENTIFIER   => $rhsRule,
      Token::TOKEN        => $token,
      Token::SEMICOLON    => $semicolon,
      Token::BEGIN_REPEAT => $beginRepeat
    ))->with(function($to, $from) {
      echo "9: Transitioned from $from to $to\n";
    });

    $fluentPda->when($semicolon)->transition(array(
      Token::IDENTIFIER => $lhsRule
    ))->terminal()->with(function($to, $from) {
      echo "4. Transitioned from $from to $to\n";
    });

    /* Define stack match rules */
    $pda->stackMatch(Token::END_REPEAT, Token::BEGIN_REPEAT);
    $pda->onTransition(array($pipe, $semicolon), function() use ($pda) {
      $pda->requireEmptyStack(); // any END_REPEAT token must occur within the same rule
    });

    /* AST stuff */
    $nodes = array('GRAMMAR');
    $pda->onTransition($lhsRule, function() use (&$nodes) {
      $currNode = end($nodes);
      echo "Pushing PRODUCTION onto $currNode\n";
      array_push($nodes, 'PRODUCTION');
    });
    $pda->onTransition($assignment, function() use (&$nodes) {
      $currNode = end($nodes);
      echo "Pushing EXPRESSION onto $currNode\n";
      array_push($nodes, 'EXPRESSION');
    });

    /* DEBUG */
    $pda->onTransition(function($node, $prev, $token) {
      echo "------------- Transitioning -------------\n";
      //echo "Transitioning to $node (" . $node->getID() . ")\n";
    });

    return $pda;
  }
}
?>
