<?php
namespace Orbison\Parser\ProductionMachine;

/*
 * A recursion-friendly atomic unit for grammar definition
 */
interface Symbol {

  /*
   * Return a unique identifier for the symbol
   */
  function getID();

  /*
   * Returns whether or not this unit is a terminal symbol
   */
  function isTerminal();

  /*
   * Returns an array of the first terminals this symbol resolves to
   * (an array of itself if this symbol itself is a terminal - base case)
   */
  function getFirstTerminals();

}
?>
