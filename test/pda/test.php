<?php
include_once('Orbison.php');

use Orbison\Parser\PDA as PDA;

$pda = new PDA();
$node1 = $pda->createNode('One');
$pda->addTransition($node1, 'Two');

$pda->onTransition(function($node) {
  echo "Transitioning to $node\n";
});
$pda->transition('Twos');
?>
