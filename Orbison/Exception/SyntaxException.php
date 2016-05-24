<?php
namespace Orbison\Exception;

class SyntaxException extends \LogicException {
  function __construct($message, $code, $line, $offset, $file=null) {
    $this->code = $code;
    $this->line = $line;
    $this->offset = $offset;
    $this->file = $file;

    parent::__construct($message);
  }

  function getErrorCode() {
    $code = str_replace(array("\r\n","\n\r","\r"), "\n", $this->code);
    $offset = $this->offset;

    $end = strpos($code, "\n",  $offset);
    return substr($code, $offset, $end-$offset);
  }
}
?>
