JClaveau\LogicalFilter\Rule\AbstractCompositeRule
===============

Composite rules are rules composed of others but are not operations.

+ In
+ Between
+ Custom rules

Composite rules are namable


* Class name: AbstractCompositeRule
* Namespace: JClaveau\LogicalFilter\Rule
* This is an **abstract** class
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)





Properties
----------


### $field

    private string $field





* Visibility: **private**


Methods
-------


### getField

    string JClaveau\LogicalFilter\Rule\AbstractCompositeRule::getField()





* Visibility: **public**




### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractCompositeRule::isAtomic()

Checks if the rule do not expect the value to be above infinity.



* Visibility: **public**




### toAtomicRules

    array JClaveau\LogicalFilter\Rule\AbstractCompositeRule::toAtomicRules()

Transforms all composite rules in the tree of operands into
atomic rules.



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




### __toString

    string JClaveau\LogicalFilter\Rule\AbstractRule::__toString()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)



