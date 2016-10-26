<?php
// <grammar> ::= ( <production> );
$grammar = $productionMachine->addProduction('grammar');
$grammar->zeroOrMore('production');

// <production> ::= [IDENTIFIER] "::=" <expression> ";";
$production = $productionMachine->addProduction('production');
$production->series(array(Token::IDENTIFIER, '::=', 'expression', ';'));

// <expression> ::= <term>  ( "|" <term> );
$expression = $productionMachine->addProduction('expression');
$expression->series(array('term', $expression->zeroOrMore(array('|', 'term')));

// <term> ::= <factor> ( <factor> );
$term = $productionMachine->addProduction('term');
$term->oneOrMore('factor');

// <factor>     ::= '"' [STRING] '"'
//                | "'" [STRING] "'"
//                | "<" [IDENTIFIER] ">"
//                | "[" [TOKEN] "]"
//                | "(" <expression> ")"
//              ;
$factor = $productionMachine->addProduction('factor');
$factor->orBlock(array(
  array('"', Token::STRING, '"'),
  array("'",  Token::STRING, "'"),
  array('<', Token::IDENTIFIER, '>'),
  array('[', Token::TOKEN, ']'),
  array('(', $prod3, ')')
));

?>
