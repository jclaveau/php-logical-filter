# php-custom-filter
This class provides a way to define complex filters freely and the tools to handle them easily

Basic rules that cannot be simplified
+ =

+ >
+ <

+ null
+ !null

+ function (regex or custom function returning a bool)

Composite rules
+ ||
+ &&
+ !

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
 - create a new trunk for every || rule by duplicating all its parents
 - combine rules
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
