JClaveau\LogicalFilter\Rule\AboveOrEqualRule
===============

a &gt;= x

This class represents a rule that expect a value to be one of the list of
possibilities only.


* Class name: AboveOrEqualRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)



Constants
----------


### operator

    const operator = 'or'







Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\AboveOrEqualRule::__construct(string $field, $minimum)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field to apply the rule on.&lt;/p&gt;
* $minimum **mixed**



### getMinimum

    mixed JClaveau\LogicalFilter\Rule\AboveOrEqualRule::getMinimum()





* Visibility: **public**




### getField

    mixed JClaveau\LogicalFilter\Rule\AboveOrEqualRule::getField()





* Visibility: **public**




### getValues

    array JClaveau\LogicalFilter\Rule\AboveOrEqualRule::getValues()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\OrRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $debug **mixed**



### rootifyDisjunctions

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\OrRule::rootifyDisjunctions()

Replace all the OrRules of the RuleTree by one OrRule at its root.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)




### aboveRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\OrRule::aboveRuleUnifySorter(\JClaveau\LogicalFilter\Rule\AboveRule $a, \JClaveau\LogicalFilter\Rule\AboveRule $b)

This is called by the unifyAtomicOperands() method to choose which AboveRule
to keep for a given field.

It's used as a usort() parameter:
+ return -1 that moves the $b variable down the array
+ return  1 moves $b up the array
+ return  0 keeps $b in the same place.

* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**



### belowRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\OrRule::belowRuleUnifySorter(\JClaveau\LogicalFilter\Rule\BelowRule $a, \JClaveau\LogicalFilter\Rule\BelowRule $b)

This is called by the unifyAtomicOperands() method to choose which BelowRule
to keep for a given field.

It's used as a usort() parameter:
+ return -1 that moves the $b variable down the array
+ return  1 moves $b up the array
+ return  0 keeps $b in the same place.

* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**



### removeInvalidBranches

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\OrRule::removeInvalidBranches()

Removes rule branches that cannot produce result like:
A = 1 && ( (B < 2 && B > 3) || (C = 8 && C = 10) ) <=> A = 1



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)




### hasSolution

    boolean JClaveau\LogicalFilter\Rule\OrRule::hasSolution()

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)



