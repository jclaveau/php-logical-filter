JClaveau\LogicalFilter\Rule\AndRule
===============

Logical conjunction:




* Class name: AndRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: JClaveau\LogicalFilter\Rule\AbstractOperationRule



Constants
----------


### operator

    const operator = 'and'







Methods
-------


### rootifyDisjunctions

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\AndRule::rootifyDisjunctions()

Replace all the OrRules of the RuleTree by one OrRule at its root.



* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\AndRule::toArray($debug)





* Visibility: **public**


#### Arguments
* $debug **mixed**



### removeInvalidBranches

    \JClaveau\LogicalFilter\Rule\AndRule JClaveau\LogicalFilter\Rule\AndRule::removeInvalidBranches()

Removes rule branches that cannot produce result like:
A = 1 || (B < 2 && B > 3) <=> A = 1



* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\Rule\AndRule::hasSolution()

Checks if a simplified AndRule has incompatible operands like:
+ a = 3 && a > 4
+ a = 3 && a < 2
+ a > 3 && a < 2



* Visibility: **public**




### simplifyDifferentOperands

    mixed JClaveau\LogicalFilter\Rule\AndRule::simplifyDifferentOperands(array $operandsByFields)

+ if A = 2 && A > 1 <=> A = 2
+ if A = 2 && A < 4 <=> A = 2



* Visibility: **protected**


#### Arguments
* $operandsByFields **array**



### aboveRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\AndRule::aboveRuleUnifySorter(\JClaveau\LogicalFilter\Rule\AboveRule $a, \JClaveau\LogicalFilter\Rule\AboveRule $b)

This is called by the unifyAtomicOperands() method to choose which AboveRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**



### belowRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\AndRule::belowRuleUnifySorter(\JClaveau\LogicalFilter\Rule\BelowRule $a, \JClaveau\LogicalFilter\Rule\BelowRule $b)

This is called by the unifyAtomicOperands() method to choose which BelowRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**


