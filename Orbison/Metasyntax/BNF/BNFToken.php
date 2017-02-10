<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Token as Token;

class BNFToken extends Token {
  const ASSIGNMENT = 'T_ASSIGNMENT';
  const STRING = 'T_STRING';
  const IDENTIFIER = 'T_IDENTIFIER';
  const PIPE = 'T_PIPE';
  const SEMICOLON = 'T_SEMICOLON';
  const TOKEN = 'T_TOKEN';
  const ACTION = 'T_ACTION';
  const EPSILON = 'T_EPSILON';
  const BEGIN_REPEAT = 'T_BEGIN_REPEAT';
  const END_REPEAT = 'T_END_REPEAT';
}
?>
