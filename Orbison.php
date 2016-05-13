<?php
$base = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Orbison' . DIRECTORY_SEPARATOR;
$parserBase = $base . 'Parser' . DIRECTORY_SEPARATOR;
$astBase = $base . 'AST' . DIRECTORY_SEPARATOR;
$metaBase = $base . 'Metasyntax' . DIRECTORY_SEPARATOR;
$bnfBase = $metaBase . 'BNF' . DIRECTORY_SEPARATOR;
$exceptionBase = $base . 'Exception' . DIRECTORY_SEPARATOR;

include_once($exceptionBase . 'ParseException.php');
include_once($exceptionBase . 'StateException.php');
include_once($exceptionBase . 'SyntaxException.php');
include_once($exceptionBase . 'TransitionException.php');
include_once($exceptionBase . 'TreeException.php');

include_once($base . 'Token.php');
include_once($base . 'Lexer.php');
include_once($base . 'Parser.php');

include_once($astBase . 'Node.php');

include_once($parserBase . 'PDA.php');
include_once($parserBase . 'StateNode.php');

include_once($bnfBase . 'BNFToken.php');
include_once($bnfBase . 'BNFLexer.php');
include_once($bnfBase . 'BNFParser.php');
include_once($bnfBase . 'AST' . DIRECTORY_SEPARATOR . 'Grammar.php');
include_once($bnfBase . 'AST' . DIRECTORY_SEPARATOR . 'Production.php');
include_once($bnfBase . 'AST' . DIRECTORY_SEPARATOR . 'Rule.php');
include_once($bnfBase . 'AST' . DIRECTORY_SEPARATOR . 'String.php');
include_once($bnfBase . 'AST' . DIRECTORY_SEPARATOR . 'Token.php');
?>
