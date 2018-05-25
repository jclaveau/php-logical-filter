# WIP: php-logical-filter
This class provides a way to define complex filters freely and the tools to handle them easily.

This is still a prototype even if some core features are working (mainly rules contradiction checking).

Quality
--------------
[![Build Status](https://travis-ci.org/jclaveau/php-logical-filter.png?branch=master)](https://travis-ci.org/jclaveau/php-logical-filter)
[![codecov](https://codecov.io/gh/jclaveau/php-logical-filter/branch/master/graph/badge.svg)](https://codecov.io/gh/jclaveau/php-logical-filter)
[![Maintainability](https://api.codeclimate.com/v1/badges/eb85279bcfb224b7af1c/maintainability)](https://codeclimate.com/github/jclaveau/php-logical-filter/maintainability)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/jclaveau/php-logical-filter/issues)
[![Viewed](http://hits.dwyl.com/jclaveau/php-logical-filter.svg)](http://hits.dwyl.com/jclaveau/php-logical-filter)


Basic rules that cannot be reduced in simpler rules
+ =
+ >
+ <
+ null
+ !null
+ function (regex or custom function returning a bool)

Operation rules
+ ||
+ &&
+ !

Composite rules
+ in
+ >=
+ <=
+ !=
+ between

-----------------------------------
Considering v a parameter, "a" and "b" two atomics rules, "A" and "B" two composite rules


-----------------------------------
Simplification
+ ! to leafs, then remove !
 - ! (v >  a) : v <= a : (v < a || a = v)
 - ! (v <  a) : v >= a : (v > a || a = v)
 - ! (  !  a) : a
 - ! (v =  a) : (v < a) || (v > a)
 - ! (B && A) : (!B && A) || (B && !A) || (!B && !A)
 - ! (B || A) : !B && !A

+ or to root
 - simplify the or root

+ combine same atomics rules to get one of each max
 - combine every atomic rule of the same kind

+ or to leafs

-----------------------------------
Aliases
+ between : < and >
+ outside : > and <
+ in      : and (=, =, =) <=> in
+         : > or <
+

-----------------------------------
Non optimized filter contains a rule-tree which is a AndRule. Each operand
of it is named by the user as "a set of rules to apply on some property".

+ This is the equivalent of the "adformat" rule of Vuble

+ Create a namable ruleSet that
+ every rule must be namable
+ operation operands have to be namable?

Related
+ https://github.com/keboola/php-filter
