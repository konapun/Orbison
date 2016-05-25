<?php
namespace Orbison\Metasyntax;

use \InvalidArgumentException as InvalidArgumentException;
use Orbison\Metasyntax\BNF\BNFLexer as BNFLexer;
use Orbison\Metasyntax\BNF\BNFParser as BNFParser;

/*
 * Builds metasyntax utilities
 */
class Factory {
  private $lexer;
  private $parser;

  const GRAMMAR_BNF = 0;

  function __construct($type=self::GRAMMAR_BNF) {
    switch ($type) {
      case self::GRAMMAR_BNF:
        $this->lexer = new BNFLexer();
        $this->parser = new BNFParser();
        break;
      default:
        throw new InvalidArgumentException("Metasyntax factory got unknown type $type");
    }
  }

  function getLexer() {
    return $this->lexer;
  }

  function getParser() {
    return $this->parser;
  }
}
?>
