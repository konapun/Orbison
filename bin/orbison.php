<?php
include_once('Orbison.php');

use Orbison\Metasyntax\Factory as GrammarFactory;

/*
 * Use Orbison as a standalone tool
 *
 */
$optsSeed = array(
  array(
    'short'         => 't',
    'long'          => 'list-tokens',
    'isOptional'    => true,
    'takesArgument' => false,
    'help'          => 'Generates a list of tokens in the source'
  ),
  array(
    'short'         => 'r',
    'long'          => 'render-tree',
    'isOptional'    => true,
    'takesArgument' => true,
    'help'          => 'Renders the resulting AST in the provided format'
  )
);

$opts = generateOpts($optsSeed);
$options = getopt($opts['short'], $opts['long']);
if (count($argv) !== 1) {
  print usage($optsSeed);
  exit(1);
}

$source = $argv[0];
$factory = new GrammarFactory(GrammarFactory::GRAMMAR_BNF);
$lexer = $factory->getLexer();
$parser = $factory->getParser();
$tokens = $lexer->tokenize($bnfMetasyntax);

foreach ($tokens as $token) {
  echo "$token (" . $token->getType() . ")\n";
}

$parser->parse($tokens);

/*
 * Build options strings from the opts struct
 */
function generateOpts($opts) {
  $shorts = array();
  $longs = array();
  foreach ($opts as $opt) {
    if (array_key_exists('short', $opt)) {
      $short = $opt['short'];
      if ($short['takesArgument']) {
        $short .= ':';
        if ($short['isOptional']) {
          $short .= ':';
        }
      }
      array_push($shorts, $short);
    }
    if (array_key_exists('long', $opt)) {
      $long = $opt['long'];
      if ($long['takesArgument']) {
        $long .= ':';
        if ($long['isOptional']) {
          $long .= ':';
        }
      }
      array_push($longs, $long);
    }
  }

  return array(
    'short' => $shorts,
    'long'  => $longs
  );
}

function usage($opts) {
  return <<<EOS
Usage: orbison [OPTIONS] <file or source>
EOS;
}
?>
