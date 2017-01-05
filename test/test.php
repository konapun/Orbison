<?php

$grammar = $machine->createProduction('grammar');
$production = $machine->createProduction('production');
$expression = $machine->createProduction('expression');
$term = $machine->createProduction('term');
$factor = $machine->createProduction('factor');

$grammar->addFactor([ $production, $grammar ]);
$grammar->addFactor(PDA::EPSILON);

$production->addFactor([ Token::IDENTIFIER, '::=', $expression ]);

$expression->addFactor($term);
$expression->addFactor([ $term, '|', $term ]);

$term->addFactor($factor);
$term->addFactor([ $factor, $term ]);

$factor->addFactor(Token::STRING);
$factor->addFactor(Token::IDENTIFIER);
$factor->addFactor(Token::TOKEN);

?>
