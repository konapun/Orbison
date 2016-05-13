<?php
namespace Orbison\AST\Renderer;

interface Renderer {

  /*
   * Create a graphical representation of the AST
   */
  function render($ast);

}
?>
