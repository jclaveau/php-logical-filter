JClaveau\LogicalFilter\Rule\AbstractAtomicRule
===============

Atomic rules are those who cannot be simplified :
+ null
+ not null
+ equal
+ above
+ below

Atomic rules are namable


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



