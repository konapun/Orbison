<?php
namespace Orbison\Test;

use Orbison\Parser as Parser;
use Orbison\Parser\PDA as PDA;
use Orbison\Parser\ProductionMachine as ProductionMachine;
use Orbison\Test\CalcLexer as CalcLexer;

class CalcParser extends Parser {

  protected function rules() {
    $productionMachine = new ProductionMachine(new PDA());

    $addRule = $productionMachine->createProduction('add');

    $addRule->series(array(CalcLexer::NUMBER, CalcLexer::PLUS, CalcLexer::NUMBER));

    $productionMachine->finalize();

    // DEBUG
    $pda->onTransition(function($node, $prev, $token) {
      echo "------------- Transitioning -------------\n";
      echo "Transitioning to $node (" . $node->getID() . ")\n";
    });

    return $pda;
  }
}
?>
