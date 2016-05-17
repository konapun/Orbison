<?php
include_once('Orbison.php');

use Orbison\Metasyntax\BNF\BNFLexer as BNFLexer;
use Orbison\Metasyntax\BNF\BNFParser as BNFParser;

$bnfMetasyntax = <<<EOS
(* comment *)
<program> ::= "start" ("more") "end" <done>;
<done> ::= "diggy" <rule2>
          | [END];
EOS;

$lexer = new BNFLexer();
$parser = new BNFParser();
$tokens = $lexer->tokenize($bnfMetasyntax);

foreach ($tokens as $token) {
  echo "$token (" . $token->getType() . ")\n";
}

$parser->parse($tokens);
?>