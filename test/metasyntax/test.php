<?php
include_once('Orbison.php');

use Orbison\Metasyntax\BNF\BNFLexer as BNFLexer;
use Orbison\Metasyntax\BNF\BNFParser as BNFParser;

$file = dirname(__FILE__) . '/examples/bflo.bflo';
$lexer = new BNFLexer();
$parser = new BNFParser();

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
