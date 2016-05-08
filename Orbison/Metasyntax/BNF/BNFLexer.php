<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Lexer as Lexer;
use Orbison\Metasyntax\BNF\BNFToken as Token;

/*
 * https://en.wikipedia.org/wiki/Backus%E2%80%93Naur_Form
 */
class BNFLexer extends Lexer {

  protected function tokens() {
    return array(
      '/^\(\*(.*?)(?=\*\))/s'    => Lexer::SKIP, // (* block comment *)
      '/^(\*\))/'                => Lexer::SKIP, // hack needed because the capture in the line above doesn't include the closing and will throw off the lexer offset
      '/^(::=)/'                 => Token::ASSIGNMENT, // ::=
      '/^"([^"]*)"/'             => Token::STRING, // "string"
      '/^(\|)/'                  => Token::PIPE, // |
      '/^(;)/'                   => Token::SEMICOLON, // ;
      '/^<([^>]*)>/'             => Token::RULE, // <rule>
      '/^\[([^\]]*)\]/'          => Token::TOKEN, // [token]
      '/^\{(^}]*)}/'             => Token::ACTION, // { action }
      '/^(\s+)/'                 => Lexer::SKIP // whitespace
    );
  }
}
?>
