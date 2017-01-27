<?php
$base = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Orbison' . DIRECTORY_SEPARATOR;
$parserBase = $base . 'Parser' . DIRECTORY_SEPARATOR;
$prodMachineBase = $parserBase . 'ProductionMachine' . DIRECTORY_SEPARATOR;
$astBase = $base . 'AST' . DIRECTORY_SEPARATOR;
$metaBase = $base . 'Metasyntax' . DIRECTORY_SEPARATOR;
$bnfBase = $metaBase . 'BNF' . DIRECTORY_SEPARATOR;
$exceptionBase = $base . 'Exception' . DIRECTORY_SEPARATOR;

include_once($exceptionBase . 'ParseException.php');
include_once($exceptionBase . 'StateException.php');
include_once($exceptionBase . 'SyntaxException.php');
include_once($exceptionBase . 'TransitionException.php');
include_once($exceptionBase . 'TreeException.php');

include_once($base . 'SourceContext.php');
include_once($base . 'Token.php');
include_once($base . 'Lexer.php');
include_once($base . 'Parser.php');

include_once($astBase . 'Node.php');
include_once($astBase . 'Builder.php');
include_once($astBase . 'Traverser.php');
include_once($astBase . 'Visitor.php');

include_once($parserBase . 'PDA.php');
include_once($parserBase . 'FluentPDA.php');
include_once($parserBase . 'StateNode.php');

include_once($parserBase . 'ProductionMachine.php');
include_once($prodMachineBase . 'Symbol.php');
include_once($prodMachineBase . 'Terminal.php');
include_once($prodMachineBase . 'Nonterminal.php');
include_once($prodMachineBase . 'Production.php');
include_once($prodMachineBase . 'Term.php');
include_once($prodMachineBase . 'Factor.php');

include_once($metaBase . 'Bundle.php');
include_once($bnfBase . 'BNF.php');
?>
