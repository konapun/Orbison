<?php
include_once('Orbison.php');
include_once('test/lexer/CalcLexer.php');
include_once('test/parser/CalcParser.php');

use Orbison\Test\CalcLexer as CalcLexer;
use Orbison\Test\CalcParser as CalcParser;

$lexer = new CalcLexer();
$parser = new CalcParser();

/*
 * Test grammar:
 *
 * <add> ::= [NUMBER] [PLUS] [NUMBER]
 */
$input_good = array('1 + 2');
$input_bad = array('1 + F', '1 - 2');

$tokens = array();
try {
  $tokens = $lexer->tokenize($input_good[0]);
}
catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

$parser->parse($tokens);
?>
