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





Properties
----------


### $operands

    protected \JClaveau\LogicalFilter\Rule\array<AbstractRule> $operands = array()

This property should never be null.



* Visibility: **protected**


### $simplified

    protected boolean $simplified = false

Enabled when the tree has been simùplified and not altered afterwards.



* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\AbstractOperationRule::__construct(array $operands)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)


#### Arguments
* $operands **array**



### getPossibilities

    array JClaveau\LogicalFilter\Rule\InRule::getPossibilities()





* Visibility: **public**




### addPossibilities

    array JClaveau\LogicalFilter\Rule\InRule::addPossibilities(array $possibilities)





* Visibility: **public**


#### Arguments
* $possibilities **array**



### copy

    \JClaveau\LogicalFilter\Rule\Rule JClaveau\LogicalFilter\Rule\AbstractRule::copy()

Clones the rule with a chained syntax.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)




### upLiftDisjunctions

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\OrRule::upLiftDisjunctions()

Replace all the OrRules of the RuleTree by one OrRule at its root.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)




### toArray

    mixed JClaveau\LogicalFilter\Rule\OrRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $debug **mixed**



### aboveRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\OrRule::aboveRuleUnifySorter(\JClaveau\LogicalFilter\Rule\AboveRule $a, \JClaveau\LogicalFilter\Rule\AboveRule $b)

This is called by the unifyOperands() method to choose which AboveRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**



### belowRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\OrRule::belowRuleUnifySorter(\JClaveau\LogicalFilter\Rule\BelowRule $a, \JClaveau\LogicalFilter\Rule\BelowRule $b)

This is called by the unifyOperands() method to choose which BelowRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**



### hasSolution

    boolean JClaveau\LogicalFilter\Rule\OrRule::hasSolution()

Checks if the tree below the current OperationRule can have solutions
or if it contains contradictory rules.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\OrRule](JClaveau-LogicalFilter-Rule-OrRule.md)




### isSimplified

    boolean JClaveau\LogicalFilter\Rule\AbstractOperationRule::isSimplified()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### addOperand

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::addOperand(\JClaveau\LogicalFilter\Rule\AbstractRule $new_operand)

Adds an operand to the logical operation (&& or ||).



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)


#### Arguments
* $new_operand **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**



### getOperands

    array JClaveau\LogicalFilter\Rule\AbstractOperationRule::getOperands()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### setOperands

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::setOperands(array $operands)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)


#### Arguments
* $operands **array**



### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractOperationRule::isAtomic()

Atomic Rules or the opposit of OperationRules: they are the leaves of
the RuleTree.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### removeNegations

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::removeNegations()

Replace NotRule objects by the negation of their operands.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### removeUselessOperations

    mixed JClaveau\LogicalFilter\Rule\AbstractOperationRule::removeUselessOperations()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### simplify

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::simplify()

Simplify the current OperationRule.

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged

* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### groupOperandsByFieldAndOperator

    array JClaveau\LogicalFilter\Rule\AbstractOperationRule::groupOperandsByFieldAndOperator()

Indexes operands by their fields and operators. This sorting is
used during the simplification step.



* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)




### unifyOperands

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::unifyOperands($unifyDifferentOperands)

Simplify the current AbstractOperationRule.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)


#### Arguments
* $unifyDifferentOperands **mixed**



### generateSimpleRule

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\Rule\AbstractRule::generateSimpleRule(string $field, string $type, $values)





* Visibility: **public**
* This method is **static**.
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $field **string**
* $type **string**
* $values **mixed**



### getRuleClass

    string JClaveau\LogicalFilter\Rule\AbstractRule::getRuleClass($rule_type)





* Visibility: **public**
* This method is **static**.
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $rule_type **mixed**



### dump

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::dump($exit)

var_dump() the rule with a chained syntax.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $exit **mixed**



### jsonSerialize

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::jsonSerialize()

For implementing JsonSerializable interface.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)



