JClaveau\LogicalFilter\Rule\AboveRule
===============

a &gt; x

Atomic rules are namable


* Class name: AboveRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)



Constants
----------


### operator

    const operator = '>'





Properties
----------


### $minimum

    protected scalar $minimum





* Visibility: **protected**


### $field

    protected string $field





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\AboveRule::__construct(string $field, $minimum)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field to apply the rule on.&lt;/p&gt;
* $minimum **mixed**



### getMinimum

    mixed JClaveau\LogicalFilter\Rule\AboveRule::getMinimum()





* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\Rule\AboveRule::hasSolution()

Checks if the rule do not expect the value to be above infinity.



* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\AboveRule::toArray($debug)





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



