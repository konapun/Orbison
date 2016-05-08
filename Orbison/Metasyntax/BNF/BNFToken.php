<?php
namespace Orbison\Metasyntax\BNF;

use Orbison\Token as Token;

class BNFToken extends Token {
  const ASSIGNMENT = 'T_ASSIGNMENT';
  const STRING = 'T_STRING';
  const RULE = 'T_RULE';
  const PIPE = 'T_PIPE';
  const SEMICOLON = 'T_SEMICOLON';
  const TOKEN = 'T_TOKEN';
  const ACTION = 'T_ACTION';
}
?>
