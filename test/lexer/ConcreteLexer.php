<?php
include_once('Orbison.php');

use Orbison\Lexer as Lexer;

class ConcreteLexer extends Lexer {
  const T_COLON = 'T_COLON';
  const T_PIPE = 'T_PIPE';
  const T_COMMA = 'T_COMMA';
  const T_STRING = 'T_STRING';
  const T_KEY = 'T_KEY';
  const T_NUMBER = 'T_NUMBER';
  const T_TRUE = 'T_TRUE';
  const T_FALSE = 'T_FALSE';

  protected function tokens() {
    return array(
      '/^;;(.*)/'                     => Lexer::SKIP, // ;; inline comment
      '/^(\|)/'                       => self::T_PIPE, // |
      '/^(,)/'                        => self::T_COMMA, // ,
      '/^\[([^\]]*)\]/'               => self::T_COMMA, // [anything] - functions as a syntactic comma
      '/^(:)/'                        => self::T_COLON, // :
      '/^"([^"]*)"/'                  => self::T_STRING, // "directive string"
      '/^([-+]?[0-9]*\.?[0-9]+)/'     => self::T_NUMBER, // -1.234
      '/^(true)/'                     => self::T_TRUE, // true
      '/^(false)/'                    => self::T_FALSE, // false
      '/^([a-z_\-][a-zA-Z_\-0-9]*)/'  => self::T_KEY,
      '/^(\s+)/'                      => Lexer::SKIP // whitespace
    );
  }
}
?>
