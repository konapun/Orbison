<?php
namespace Orbison\AST;

/*
 *
 */
abstract class Visitor {

  function enterNode($node) {}

  function leaveNode($node) {}
}
 ?>
