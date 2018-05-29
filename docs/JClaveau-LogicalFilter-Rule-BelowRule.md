JClaveau\LogicalFilter\Rule\BelowRule
===============

a &lt; x




* Class name: BelowRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)



Constants
----------


### operator

    const operator = '<'





Properties
----------


### $maximum

    protected scalar $maximum





* Visibility: **protected**


### $field

    protected string $field





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\BelowRule::__construct(string $field, $maximum)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field to apply the rule on.&lt;/p&gt;
* $maximum **mixed**



### hasSolution

    boolean JClaveau\LogicalFilter\Rule\BelowRule::hasSolution()

Checks if the rule do not expect the value to be above infinity.



* Visibility: **public**




### getMaximum

    mixed JClaveau\LogicalFilter\Rule\BelowRule::getMaximum()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\BelowRule::toArray($debug)





* Visibility: **public**


#### Arguments
* $debug **mixed**



### getField

    string JClaveau\LogicalFilter\Rule\AbstractAtomicRule::getField()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractAtomicRule::isAtomic()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




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



