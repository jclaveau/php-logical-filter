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

    mixed JClaveau\LogicalFilter\Rule\NotRule::__construct($operand)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $operand **mixed**



### getField

    mixed JClaveau\LogicalFilter\Rule\NotInRule::getField()





* Visibility: **public**




### getPossibilities

    array JClaveau\LogicalFilter\Rule\NotInRule::getPossibilities()





* Visibility: **public**




### toArray

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\NotRule::toArray($debug)

Replace all the OrRules of the RuleTree by one OrRule at its root.



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


