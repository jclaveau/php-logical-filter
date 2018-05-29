JClaveau\LogicalFilter\Rule\EqualRule
===============

a &gt; x

Atomic rules are namable


* Class name: EqualRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)



Constants
----------


### operator

    const operator = '='





Properties
----------


### $value

    protected mixed $value





* Visibility: **protected**


### $field

    protected string $field





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Rule\EqualRule::__construct(string $field, array $value)





* Visibility: **public**


#### Arguments
* $field **string** - &lt;p&gt;The field to apply the rule on.&lt;/p&gt;
* $value **array** - &lt;p&gt;The value the field can equal to.&lt;/p&gt;



### getValue

    mixed JClaveau\LogicalFilter\Rule\EqualRule::getValue()





* Visibility: **public**




### toArray

    mixed JClaveau\LogicalFilter\Rule\EqualRule::toArray($debug)





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



