<?php
namespace Orbison\Metasyntax\PMTest;

$testBase = $base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
include_once($testBase . 'Token.php');
include_once($testBase . 'PMTestLexer.php');
include_once($testBase . 'PMTestParser.php');

use Orbison\Metasyntax\Bundle as Bundle;
use Orbison\Metasyntax\PMTest\PMTestLexer as PMTestLexer;
use Orbison\Metasyntax\PMTest\PMTestParser as PMTestParser;

class PMTest implements Bundle {
  private $lexer;
  private $parser;

  function __construct() {
    $this->lexer = new PMTestLexer();
    $this->parser = new PMTestParser();
  }

  function getLexer() {
    return $this->lexer;
  }

  function getParser() {
    return $this->parser;
  }
}
?>
