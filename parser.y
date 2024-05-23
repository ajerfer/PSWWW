%{
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
void yyerror(char *);
extern FILE *yyin;       
extern FILE *yyout;
extern int yylineno;
int line = 0;
%}

%union {
int valor_entero;
double valor_real;
char * texto;
}


%token STAR
%token BS
%token EQ
%token PLUS
%token PUBLIC_CLASS
%token RETURN
%token DATATYPE
%token NAME
%token COMP_OPERATOR
%token MODIFIER
%token VOID
%token DO
%token WHILE
%token IF ELSE ELSEIF
%token SWITCH CASE DEFAULT
%token FOR
%token PRINT
%token BREAK
%token METHOD
%token LEFT_PAREN
%token RIGHT_PAREN
%token LEFT_BRACE
%token RIGHT_BRACE
%token SEMICOLON
%token IDENTIFIER
%token DOUBLELIT
%token INTLIT
%token STRLITERAL
%token CHARLITERAL
%left "-" "+" "*" "/"

%start program

%%


program: class class_list
        ;

class_list: class
        | class_list
        |
        ;

class:  PUBLIC_CLASS LEFT_BRACE class_body RIGHT_BRACE
        ;

class_body:     variable_declaration method_declaration class_list
        | variable_declaration class_list
        | method_declaration class_list
        | class_list
        ;



statements: statement
        | statements statement
        ;

statement:  assigment_statement
        | loop_statement
        | control_statement
        | print_statement
        | return_statement
        | loop_termination_statemet

assigment_statement:    NAME "=" expression ";"

control_statement:  if_statement
        | switch_statement

if_statement: IF LEFT_PAREN expression RIGHT_PAREN LEFT_BRACE statements RIGHT_BRACE elseif_list else_part
        ;

elseif_list:    
        | ELSEIF LEFT_PAREN expression RIGHT_PAREN LEFT_BRACE statements RIGHT_BRACE elseif_list
        ;

else_part:  
        | ELSE LEFT_BRACE statements RIGHT_BRACE
        ;

switch_statement:       SWITCH LEFT_PAREN expression RIGHT_PAREN LEFT_BRACE case_list default_part RIGHT_BRACE
        ;

case_list:
        | case_list CASE expression ":" statements
        ;

default_part:
        | DEFAULT ":" statements
        ;

loop_statement: do_while_statement
        | for_statement
        ;

do_while_statement: DO LEFT_BRACE statements RIGHT_BRACE WHILE LEFT_PAREN expression RIGHT_BRACE ";"
        ;

for_statement: FOR LEFT_PAREN expression ";" expression ";" expression RIGHT_PAREN LEFT_BRACE statements RIGHT_BRACE 
        ;

print_statement: PRINT LEFT_PAREN STRLITERAL "," var_list RIGHT_PAREN ";"
        | PRINT LEFT_PAREN STRLITERAL RIGHT_PAREN ";"
        ;

return_statement: RETURN expression ";"
        ;

loop_termination_statemet: BREAK ";"
        ;