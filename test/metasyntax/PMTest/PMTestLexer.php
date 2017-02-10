<?php
namespace Orbison\Metasyntax\PMTest;

use Orbison\Lexer as Lexer;
use Orbison\Metasyntax\PMTest\PMToken as Token;

class PMTestLexer extends Lexer {

  protected function tokens() {
    return array(
      '/^"([^"]*)"/' => Token::STRING, // "string"
      '/^(hello)/'   => Token::HELLO, // hello
      '/^(world)/'   => Token::WORLD, // world
      '/^(,)/'       => Token::COMMA, // ,
      '/^(!)/'       => Token::EXCLAMATION, // !
      '/^(\s+)/'     => Lexer::SKIP // whitespace
    );
  }
}
?>
