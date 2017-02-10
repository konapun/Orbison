<?php
include_once('Orbison.php');

$testBase = $base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
include_once($testBase . 'PMTest' . DIRECTORY_SEPARATOR . 'PMTest.php');

use Orbison\Metasyntax\PMTest\PMTest as Bundle;

$file = dirname(__FILE__) . '/examples/simple.bnf';
$bundle = new Bundle();
$lexer = $bundle->getLexer();
$parser = $bundle->getParser();

$tokens = array();
try {
  $tokens = $lexer->tokenize($file);
}
catch (Exception $e) {
  print $e->getMessage() . " at " . $e->getErrorCode() . " (line " . $e->getLine() . ")\n";
  exit(1);
}

foreach ($tokens as $token) {
  print "$token (" . $token->getType() . ")\n";
}

$parser->parse($tokens);
?>
