<?php
include_once('test/lexer/ConcreteLexer.php');

$lexer = new ConcreteLexer();
$tokens = $lexer->tokenize(file_get_contents('test/lexer/test.lang'));

foreach ($tokens as $token) {
  echo "$token (" . $token->getType() . ")\n";
}
?>
