JClaveau\LogicalFilter\Rule\AndRule
===============

Logical conjunction:




* Class name: AndRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)



Constants
----------


### operator

    const operator = 'and'





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


### upLiftDisjunctions

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\AndRule::upLiftDisjunctions()

Replace all the OrRules of the RuleTree by one OrRule at its root.



* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\AndRule::toArray($debug)





* Visibility: **public**


#### Arguments
* $debug **mixed**



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

This is called by the unifyOperands() method to choose which AboveRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\AboveRule](JClaveau-LogicalFilter-Rule-AboveRule.md)**



### belowRuleUnifySorter

    integer JClaveau\LogicalFilter\Rule\AndRule::belowRuleUnifySorter(\JClaveau\LogicalFilter\Rule\BelowRule $a, \JClaveau\LogicalFilter\Rule\BelowRule $b)

This is called by the unifyOperands() method to choose which BelowRule
to keep for a given field.

It's used as a usort() parameter.

* Visibility: **protected**


#### Arguments
* $a **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**
* $b **[JClaveau\LogicalFilter\Rule\BelowRule](JClaveau-LogicalFilter-Rule-BelowRule.md)**



### __construct

    mixed JClaveau\LogicalFilter\Rule\AbstractOperationRule::__construct(array $operands)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)


#### Arguments
* $operands **array**



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



### copy

    \JClaveau\LogicalFilter\Rule\Rule JClaveau\LogicalFilter\Rule\AbstractRule::copy()

Clones the rule with a chained syntax.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)




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



