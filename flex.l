%{
#include "myparser.tab.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
%}

%x  comment

underscore  "_"
digit   [0-9]
letter  [a-zA-Z]
capitalletter   [A-Z]
chartype    "char"
inttype "int"
doubletype  "double"
booleantype "boolean"
datatype {inttype}|{doubletype}|{booleantype}|{chartype}
modifier    "public"|"private"
comparative "<"|">"|"=="|"!="|"&&"|"||"
linecomment "//"
commentbegin    "/*"
name    {capitalletter}({digit}|{letter}|{underscore})*
identifier  ({underscore}|{letter})({letter}|{digit}|{underscore})* 
strliteral  \"[^\"]*\"
doubleliteral   [0-9]+"."[0-9]+
charliteral \'[^\']\'
boolliteral true|false
intliteral  [0-9]+

%%


"*"                 { printf("%s", yytext); return STAR; }
"/"                 { printf("%s", yytext); return BS; }
"="                 { printf("%s", yytext); return EQ; }
"+"                 { printf("%s", yytext); return PLUS; }
"public class"      { printf("%s", yytext); return PUBLIC_CLASS; }
"return"            { printf("%s", yytext); return RETURN; }
{datatype}          { printf("%s", yytext); return DATATYPE; }
{name}              { printf("%s", yytext); return NAME; }
{comparative}       { printf("%s", yytext); return COMP_OPERATOR; }
{modifier}          { printf("%s", yytext); return MODIFIER; }
"void"              { printf("%s", yytext); return VOID; }
"do"                { printf("%s", yytext); return DO; }
"while"             { printf("%s", yytext); return WHILE; }
"if"                { printf("%s", yytext); return IF; }
"else"              { printf("%s", yytext); return ELSE; }
"elseif"            { printf("%s", yytext); return ELSEIF; }
"switch"            { printf("%s", yytext); return SWITCH; }
"case"              { printf("%s", yytext); return CASE; }
"default"           { printf("%s", yytext); return DEFAULT; }
"for"               { printf("%s", yytext); return FOR; }
"out.print"         { printf("%s", yytext); return PRINT; }
"break"             { printf("%s", yytext); return BREAK; }
"method"            { printf("%s", yytext); return METHOD; }
"("                 { return LEFT_PAREN; }
")"                 { return RIGHT_PAREN; }
"{"                 { return LEFT_BRACE; }
"}"                 { return RIGHT_BRACE; }
";"                 { return SEMICOLON; }
{identifier}        { printf("%s", yytext); return IDENTIFIER; }
{doubleliteral}     { printf("%s", yytext); return DOUBLELIT; }
{intliteral}        { printf("%s", yytext); return INTLIT; }
{strliteral}        { printf("%s", yytext); return STRLITERAL; }
{charliteral}       { printf("%s", yytext); return CHARLITERAL; }


{linecomment}       BEGIN(comment);
<comment>\n         BEGIN(INITIAL);
{commentbegin}      BEGIN(comment);
<comment>"*/"    BEGIN(INITIAL);

%%

