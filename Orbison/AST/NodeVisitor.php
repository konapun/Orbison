<?php
namespace Orbison\AST;

/*
 *
 */
abstract class NodeVisitor {

  function enterNode($node) {}

  function leaveNode($node) {}
}
 ?>
