# Orbison
Tools for language implementation

Orbison can integrate into your project in two ways:

  1. Using code generation to create an automaton from a language definition file that can parse the language described by the definition (as source code)
  2. Using Orbison as a library

## Defining your language

### Defining Tokens
Orbison first converts the source string into a token stream using regular expressions


### Defining Rules
Orbison supports defining the input language through a variety of different types of metasyntax. The default metasyntax is a variant of Backus-Naur Form (BNF) that subsequent metasyntax languages build upon.

#### BNF
Example for a lisp-like language
```
<exprs> ::= <expr> <exprs>;
<list> ::= "(" <exprs> ")";
<expr> ::=  <atom>
          | <list>
        ;
<atom> ::=
    [IDENTIFIER]
  | [CONSTANT]
  | [NIL]
  | "+"
  | "-"
  | "*"
  | "^"
  | "/"
  ;
```

With the folliwing notation:
  * `<rule>`: Rule
  * `"string"`: String
  * `[TOKEN]`: Token
  * `|`: Alternative
  * `;`: Rule terminator

The result is either

 1. The pushdown automaton (PDA) defined by the BNF grammar
 2. Source code for the PDA defined by the BNF  grammar

## Code Generation
Currently, only generating PHP code is supported, using [nikic's PHP Parser](https://github.com/nikic/PHP-Parser), but additional targets will be added in the future.

```php
$codegen = new Orbison\Codegen\PHP();
$phpCode = $codegen->generateCode($ast);
```

## Putting It All Together
```php
$compiler = new Orbison\Compiler(array(
  'meta'   => Orbison\Metasyntax\Language::BNF,
  'target' => Orbison\Codegen\Target::PHP
));
$compiler->compile('language.bnf');
```

# Resources
http://www.cs.uwm.edu/classes/cs631/slides/02BNF.pdf
