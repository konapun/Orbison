<?php
// <grammar> ::= ( <production> );
$prod1 = $productionMachine->addProduction();
$prod1->zeroOrMore($prod2);

// <production> ::= [IDENTIFIER] "::=" <expression> ";";
$prod2 = $productionMachine->addProduction();
$prod2
  ->transition(Token::IDENTIFIER)
  ->transition('::=')
  ->transition($prod3)
  ->transition(';');

// <expression> ::= <term>  ( "|" <term> );
$prod3 = $productionMachine->addProduction();
$prod3
  ->transition($prod4)
  ->zeroOrMore(function() {
    $this
      ->transition('|')
      ->transition($prod4);
  });

// <term> ::= <factor> ( <factor> );
$prod4 = $productionMachine->addProduction();
$prod4->oneOrMore($prod5);

// <factor>     ::= '"' [STRING] '"'
//                | "'" [STRING] "'"
//                | "<" [IDENTIFIER] ">"
//                | "[" [TOKEN] "]"
//                | "(" <expression> ")"
//              ;
$prod5 = $productionMachine->addProduction();
$prod5->orBlock(array(
  array('"', Token::STRING, '"'),
  array("'",  Token::STRING, "'"),
  array('<', Token::IDENTIFIER, '>'),
  array('[', Token::TOKEN, ']'),
  array('(', $prod3, ')')
));

?>
