JClaveau\LogicalFilter\Rule\NotRule
===============

Logical negation:




* Class name: NotRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: JClaveau\LogicalFilter\Rule\AbstractOperationRule



Constants
----------


### operator

    const operator = 'not'







Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\NotRule::__construct(\JClaveau\LogicalFilter\Rule\AbstractRule $operand)





* Visibility: **public**


#### Arguments
* $operand **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**



### negateOperand

    array JClaveau\LogicalFilter\Rule\NotRule::negateOperand($remove_generated_negations)

Transforms all composite rules in the tree of operands into
atomic rules.



* Visibility: **public**


#### Arguments
* $remove_generated_negations **mixed**



### unifyAtomicOperands

    \JClaveau\LogicalFilter\Rule\NotRule JClaveau\LogicalFilter\Rule\NotRule::unifyAtomicOperands($unifyDifferentOperands)

Not rules can only have one operand.



* Visibility: **public**


#### Arguments
* $unifyDifferentOperands **mixed**



### toArray

    mixed JClaveau\LogicalFilter\Rule\NotRule::toArray($debug)





* Visibility: **public**


#### Arguments
* $debug **mixed**


