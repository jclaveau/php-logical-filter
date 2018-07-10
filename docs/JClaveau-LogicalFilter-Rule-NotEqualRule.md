JClaveau\LogicalFilter\Rule\NotEqualRule
===============

a != x




* Class name: NotEqualRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)



Constants
----------


### operator

    const operator = 'not'





Properties
----------


### $null_field

    protected string $null_field = null





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\NotRule::__construct(\JClaveau\LogicalFilter\Rule\AbstractRule $operand)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $operand **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**



### rootifyDisjunctions

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\NotEqualRule::rootifyDisjunctions()

Replace all the OrRules of the RuleTree by one OrRule at its root.



* Visibility: **public**




### getField

    mixed JClaveau\LogicalFilter\Rule\NotEqualRule::getField()





* Visibility: **public**




### getValue

    mixed JClaveau\LogicalFilter\Rule\NotEqualRule::getValue()





* Visibility: **public**




### getValues

    mixed JClaveau\LogicalFilter\Rule\NotEqualRule::getValues()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\NotRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\NotRule](JClaveau-LogicalFilter-Rule-NotRule.md)


#### Arguments
* $debug **mixed**



### hasSolution

    boolean JClaveau\LogicalFilter\Rule\NotEqualRule::hasSolution()

By default, every atomic rule can have a solution by itself



* Visibility: **public**




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


