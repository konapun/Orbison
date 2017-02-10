<?php
namespace Orbison;

/*
 * Class that bundles together information for a source code snippet
 */
class SourceContext {
  private $source;
  private $lineNumber;
  private $offset;
  private $file;

  function __construct($source, $line, $offset, $file) {
    $this->source = $source;
    $this->lineNumber = $line;
    $this->offset = $offset;
    $this->file = $file;
  }

  function getSource() {
    return $this->source;
  }

  function getLineNumber() {
    return $this->lineNumber;
  }

  function getOffset() {
    return $this->offset;
  }

  function getFile() {
    return $this->file;
  }
}
?>
