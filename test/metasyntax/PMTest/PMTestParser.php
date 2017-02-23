<?php
namespace Orbison\Metasyntax\PMTest;

use Orbison\Parser as Parser;
use Orbison\Parser\ProductionMachine as ProductionMachine;
use Orbison\Metasyntax\PMTest\PMToken as Token;

class PMTestParser extends Parser {

  /*
   * <phrase> ::= "hello" "," <target> <punctuation> <phrase>
   *            | "done" "!";
   * <target> ::= "world"
   *           | [STRING];
   * <punctuation> ::= "!"
   *                 | "."
   *                 | "?";
   */
  protected function rules() {
    $productionMachine = new ProductionMachine();

    $phrase = $productionMachine->createProduction('phrase');
    $target = $productionMachine->createProduction('target');
    $punctuation = $productionMachine->createProduction('punctuation');

    $phrase->addFactor(array( Token::HELLO, Token::COMMA, $target, $punctuation, $phrase ))->onMatch(function($matches) {
      // print "Matched first term for production PHRASE with matches!\n";
      // var_dump($matches);
    });
    $phrase->addFactor(array( Token::DONE, Token::EXCLAMATION ))->onMatch(function($matches) {
      // print "Matched second term for production PHRASE!\n";
    });

    $target->addFactor(Token::WORLD)->onMatch(function($matches) {
      // print "Matched first term for production TARGET!\n";
    });
    $target->addFactor(Token::STRING)->onMatch(function($matches) {
      // print "Matched second term for production TARGET!\n";
    });

    $punctuation->addFactor(Token::EXCLAMATION)->onMatch(function($matches) {
      // print "Matched first term for production PUNCTUATION!\n";
    });
    $punctuation->addFactor(Token::PERIOD)->onMatch(function($matches) {
      // print "Matched second term for production PUNCTUATION!\n";
    });
    $punctuation->addFactor(Token::QUESTION)->onMatch(function($matches) {
      // print "Matched third term for production PUNCTUATION!\n";
    });

    $pda = $productionMachine->exportPDA();

    /* DEBUG */
    $pda->onTransition(function($node, $prev, $token) {
      echo "------------- Transitioning -------------\n";
      echo "Transitioning from node $prev on token $token to node $node\n";
    });

    return $pda;
  }
}
?>
