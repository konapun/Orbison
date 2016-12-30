<?php
namespace Orbison\Test;

use Orbison\Lexer as Lexer;
use Orbison\Metasyntax\BNF\BNFToken as Token;

class CalcLexer extends Lexer {
  const PLUS = 'T_PLUS';
  const MINUS = 'T_MINUS';
  const NUMBER = 'T_NUMBER';

  protected function tokens() {
    return array(
      '/^(\+)/'                  => CalcLexer::PLUS,
      '/^(-)/'                   => CalcLexer::MINUS,
      '/^(\d+)/'                 => CalcLexer::NUMBER,
      '/^(\s+)/'                 => Lexer::SKIP // whitespace
    );
  }
}
?>
