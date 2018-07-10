JClaveau\LogicalFilter\Rule\InRule
===============

This class represents a rule that expect a value to belong to a list of others.

This class represents a rule that expect a value to be one of the list of
possibilities only.


* Class name: InRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)



Constants
----------


### operator

    const operator = 'or'





### simplification_threshold

    const simplification_threshold = 20





Properties
----------


### $field

    protected string $field





* Visibility: **protected**


### $simplification_allowed

    protected string $simplification_allowed = true





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\InRule::__construct(string $field, mixed $possibilities)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field to apply the rule on.&lt;/p&gt;
* $possibilities **mixed** - &lt;p&gt;The values the field can belong to.&lt;/p&gt;



### getField

    string JClaveau\LogicalFilter\Rule\InRule::getField()





* Visibility: **public**




### renameFields

    \JClaveau\LogicalFilter\Rule\AbstractAtomicRule JClaveau\LogicalFilter\Rule\InRule::renameFields($renamings)





* Visibility: **public**


#### Arguments
* $renamings **mixed**



### getPossibilities

    array JClaveau\LogicalFilter\Rule\InRule::getPossibilities()





* Visibility: **public**




### addPossibilities

    \JClaveau\LogicalFilter\Rule\InRule JClaveau\LogicalFilter\Rule\InRule::addPossibilities($possibilities)





* Visibility: **public**


#### Arguments
* $possibilities **mixed**



### setPossibilities

    \JClaveau\LogicalFilter\Rule\InRule JClaveau\LogicalFilter\Rule\InRule::setPossibilities($possibilities)





* Visibility: **public**


#### Arguments
* $possibilities **mixed**



### getValues

    array JClaveau\LogicalFilter\Rule\InRule::getValues()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\OrRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $debug **mixed**



### isSimplificationAllowed

    mixed JClaveau\LogicalFilter\Rule\InRule::isSimplificationAllowed()





* Visibility: **public**




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



