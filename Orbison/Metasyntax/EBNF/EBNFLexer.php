<?php
namespace Orbison\Metasyntax\EBNF;

use Orbison\Lexer as Lexer;
use EBNFToken as Token;

/*
 * https://en.wikipedia.org/wiki/Extended_Backus%E2%80%93Naur_Form
 */
class EBNFLexer extends Lexer {

  protected function tokens() {
    return array(
      '/^\(\*(.*?)(?=\*\))/s'        => Lexer::SKIP, // (* comment *)
      '/^=/'                         => Token::EQUALS, // =
      '/^,/'                         => Token::COMMA, // ,
      '/^;/'                         => Token::SEMICOLON, // ;
      '/^\./'                        => Token::PERIOD, // .
      '/^\|/'                        => Token::PIPE, // |
      '/^([a-z_\-][a-zA-Z_\-0-9]*)/' => Token::RULE,
    );
  }
}
?>
