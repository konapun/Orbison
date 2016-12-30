<?php
namespace Orbison\Test;

use Orbison\Parser as Parser;
use Orbison\Parser\ProductionMachine as ProductionMachine;
use Orbison\Test\CalcLexer as CalcLexer;

class CalcParser extends Parser {

  protected function rules($pda, $ast) {
    $productionMachine = new ProductionMachine($pda);

    $addRule = $productionMachine->createProduction('add');

    $addRule->series(array(CalcLexer::NUMBER, CalcLexer::PLUS, CalcLexer::NUMBER));

    $productionMachine->finalize();

    // DEBUG
    $pda->onTransition(function($node, $prev, $token) {
      echo "------------- Transitioning -------------\n";
      echo "Transitioning to $node (" . $node->getID() . ")\n";
    });
  }
}
?>
