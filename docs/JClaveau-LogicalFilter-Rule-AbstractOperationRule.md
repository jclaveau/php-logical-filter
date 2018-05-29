JClaveau\LogicalFilter\Rule\AbstractOperationRule
===============

Operation rules:
+ Or
+ And
+ Not




* Class name: AbstractOperationRule
* Namespace: JClaveau\LogicalFilter\Rule
* This is an **abstract** class
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)





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


#### Arguments
* $operands **array**



### isSimplified

    boolean JClaveau\LogicalFilter\Rule\AbstractOperationRule::isSimplified()





* Visibility: **public**




### addOperand

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::addOperand(\JClaveau\LogicalFilter\Rule\AbstractRule $new_operand)

Adds an operand to the logical operation (&& or ||).



* Visibility: **public**


#### Arguments
* $new_operand **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**



### getOperands

    array JClaveau\LogicalFilter\Rule\AbstractOperationRule::getOperands()





* Visibility: **public**




### setOperands

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::setOperands(array $operands)





* Visibility: **public**


#### Arguments
* $operands **array**



### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractOperationRule::isAtomic()

Atomic Rules or the opposit of OperationRules: they are the leaves of
the RuleTree.



* Visibility: **public**




### removeNegations

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::removeNegations()

Replace NotRule objects by the negation of their operands.



* Visibility: **public**




### removeUselessOperations

    mixed JClaveau\LogicalFilter\Rule\AbstractOperationRule::removeUselessOperations()





* Visibility: **public**




### simplify

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::simplify()

Simplify the current OperationRule.

+ If an OrRule or an AndRule contains only one operand, it's equivalent
  to it.
+ If an OrRule has an other OrRule as operand, they can be merged
+ If an AndRule has an other AndRule as operand, they can be merged

* Visibility: **public**




### groupOperandsByFieldAndOperator

    array JClaveau\LogicalFilter\Rule\AbstractOperationRule::groupOperandsByFieldAndOperator()

Indexes operands by their fields and operators. This sorting is
used during the simplification step.



* Visibility: **protected**




### unifyOperands

    \JClaveau\LogicalFilter\Rule\AbstractOperationRule JClaveau\LogicalFilter\Rule\AbstractOperationRule::unifyOperands($unifyDifferentOperands)

Simplify the current AbstractOperationRule.



* Visibility: **public**


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



