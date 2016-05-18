<?php
$bnfBase = $base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$astBase = $bnfBase . 'AST' . DIRECTORY_SEPARATOR;

include_once($bnfBase . 'BNFToken.php');
include_once($bnfBase . 'BNFLexer.php');
include_once($bnfBase . 'BNFParser.php');

include_once($astBase . 'Expression.php');
include_once($astBase . 'Factor.php');
include_once($astBase . 'Grammar.php');
include_once($astBase . 'Identifier.php');
include_once($astBase . 'Production.php');
include_once($astBase . 'String.php');
include_once($astBase . 'Term.php');
include_once($astBase . 'Token.php');
?>
