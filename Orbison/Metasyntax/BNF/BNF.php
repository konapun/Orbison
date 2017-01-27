<?php
namespace Orbison\Metasyntax\BNF;

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

use Orbison\Metasyntax\Bundle as Bundle;
use Orbison\Metasyntax\BNF\BNFLexer as BNFLexer;
use Orbison\Metasyntax\BNF\BNFParser as BNFParser;

class BNF implements Bundle {
  private $lexer;
  private $parser;

  function __construct() {
    $this->lexer = new BNFLexer();
    $this->parser = new BNFParser();
  }

  function getLexer() {
    return $this->lexer;
  }

  function getParser() {
    return $this->parser;
  }
}
?>
