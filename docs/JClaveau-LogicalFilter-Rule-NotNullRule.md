JClaveau\LogicalFilter\Rule\NotNullRule
===============

Atomic rules are those who cannot be simplified :
+ null
+ not null
+ equal
+ above
+ below

Atomic rules are namable


* Class name: NotNullRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)





Properties
----------


### $field

    protected string $field





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\NotNullRule::__construct(string $field)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field that should not be null.&lt;/p&gt;



### hasSolution

    boolean JClaveau\LogicalFilter\Rule\NotNullRule::hasSolution()





* Visibility: **public**




### getField

    string JClaveau\LogicalFilter\Rule\AbstractAtomicRule::getField()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




### isAtomic

    boolean JClaveau\LogicalFilter\Rule\AbstractAtomicRule::isAtomic()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




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



