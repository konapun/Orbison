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
      '/^(done)/'    => Token::DONE, // done
      '/^(,)/'       => Token::COMMA, // ,
      '/^(!)/'       => Token::EXCLAMATION, // !
      '/^(\.)/'      => Token::PERIOD, // .
      '/^(\?)/'      => Token::QUESTION, // ?
      '/^(\s+)/'     => Lexer::SKIP // whitespace
    );
  }
}
?>
