<?php
namespace Orbison\AST\Exporter;

use Orbison\AST\Exporter\Exporter as Exporter;
use Orbison\AST\NodeVisitor as Visitor;

/*
 * Export the AST in JSON format
 */
class JSONExporter implements Exporter {

  function export(Node $ast) {
    $visitor = new Visitor();
    $tree = array();
    $visitor->visit($ast, function($node) use ($tree) {
      // TODO
    });
    return json_encode($tree);
  }
}
?>
