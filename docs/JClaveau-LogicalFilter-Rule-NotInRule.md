JClaveau\LogicalFilter\Rule\NotInRule
===============

a ! in x




* Class name: NotInRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)



Constants
----------


### operator

    const operator = 'not'







Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\NotRule::__construct(\JClaveau\LogicalFilter\Rule\AbstractRule $operand)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $operand **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**



### getField

    mixed JClaveau\LogicalFilter\Rule\NotInRule::getField()





* Visibility: **public**




### getPossibilities

    array JClaveau\LogicalFilter\Rule\NotInRule::getPossibilities()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\NotRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $debug **mixed**



### negateOperand

    array JClaveau\LogicalFilter\Rule\NotRule::negateOperand($remove_generated_negations)

Transforms all composite rules in the tree of operands into
atomic rules.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $remove_generated_negations **mixed**



### unifyAtomicOperands

    \JClaveau\LogicalFilter\Rule\NotRule JClaveau\LogicalFilter\Rule\NotRule::unifyAtomicOperands($unifyDifferentOperands)

Not rules can only have one operand.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $unifyDifferentOperands **mixed**


