<?php
namespace Orbison\Metasyntax;

/*
 * A bundle that packages together a metasyntax's lexer and parser
 */
interface Bundle {

  function getLexer();

  function getParser();
}
?>
