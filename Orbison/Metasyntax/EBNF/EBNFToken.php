<?php
namespace Orbison\Metasyntax\EBNF;

use Bauplan\Token as Token;

class EBNFToken extends Token {
  const T_EQUALS = 'T_EQUALS';
  const T_COMMA  = 'T_COMMA';
  const T_SEMICOLON = 'T_SEMICOLON';
  const T_PERIOD = 'T_PERIOD';
  const T_PIPE = 'T_PIPE';
  const T_STRING = 'T_STRING';
  const T_RULE = 'T_RULE';
}
?>
