<?php
namespace Orbison\AST\Exporter;

use Orbison\AST\Node as Node;

/*
 *
 */
interface Exporter {
  function export(Node $ast);
}
?>
