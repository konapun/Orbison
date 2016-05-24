<?php
include_once('Orbison.php');

use Orbison\Metasyntax\BNF\BNFLexer as BNFLexer;
use Orbison\Metasyntax\BNF\BNFParser as BNFParser;

$bnfMetasyntax = <<<EOS
(* Micro CFG from sample *)
<program>   ::= "begin" <stat-list> "end" {action};
<stat-list> ::= <statement>  ( <statement> );
<statement> ::= [id] ":=" <expr> ";"
              | "read" "(" <id-list> ")" ";"
              | "write" "(" <expr-list> ")" ";"
            ;
<id-list>   ::= [id] ( "," [id] );
<expr-list> ::= <expr> ( "," <expr> );
<expr>      ::= <primary> ( <addop> <primary> );
<primary>   ::= ( <expr> )
              | [id]
              | [intliteral]
            ;
<addop>     ::= "+"
              | "-"
            ;
EOS;

$lexer = new BNFLexer();
$parser = new BNFParser();
$tokens = $lexer->tokenize($bnfMetasyntax);

foreach ($tokens as $token) {
  echo "$token (" . $token->getType() . ")\n";
}

$parser->parse($tokens);
?>
