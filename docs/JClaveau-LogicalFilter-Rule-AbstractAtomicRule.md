JClaveau\LogicalFilter\Rule\AbstractAtomicRule
===============

Atomic rules are those who cannot be simplified:
+ null
+ not null
+ equal
+ above
+ below
Atomic rules are namable.




* Class name: AbstractAtomicRule
* Namespace: JClaveau\LogicalFilter\Rule
* This is an **abstract** class
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)





Properties
----------


### $field

    protected string $field





* Visibility: **protected**


Methods
-------


### getField

    string JClaveau\LogicalFilter\Rule\AbstractAtomicRule::getField()





* Visibility: **public**




### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractAtomicRule::isAtomic()





* Visibility: **public**




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



