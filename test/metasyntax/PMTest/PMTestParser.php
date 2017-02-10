<?php
namespace Orbison\Metasyntax\PMTest;

use Orbison\Parser as Parser;
use Orbison\Parser\ProductionMachine as ProductionMachine;
use Orbison\Metasyntax\PMTest\PMToken as Token;

class PMTestParser extends Parser {

  /*
   * <phrase> ::= "hello" "," <target> "!";
   * <target> ::= "world"
   *           | [STRING];
   */
  protected function rules() {
    $productionMachine = new ProductionMachine();

    $phrase = $productionMachine->createProduction('phrase');
    $target = $productionMachine->createProduction('target');

    $phrase->addFactor(array( Token::HELLO, Token::COMMA, $target, Token::EXCLAMATION ));
    $target->addFactor(Token::WORLD);
    $target->addFactor(Token::STRING);

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
