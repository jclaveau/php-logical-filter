JClaveau\LogicalFilter\Rule\NotNullRule
===============

Atomic rules are those who cannot be simplified (so already are):
+ null
+ not null
+ equal
+ above
+ below
Atomic rules are related to a field.




* Class name: NotNullRule
* Namespace: JClaveau\LogicalFilter\Rule
* Parent class: [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)





Properties
----------


### $field

    protected string $field





* Visibility: **protected**


### $ruleAliases

    protected array $ruleAliases = array('!' => 'not', '=' => 'equal', '>' => 'above', '<' => 'below')





* Visibility: **protected**
* This property is **static**.


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




### isSimplified

    boolean JClaveau\LogicalFilter\Rule\AbstractAtomicRule::isSimplified()

Atomic rules are always simplified



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




### findSymbolicOperator

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::findSymbolicOperator($english_operator)





* Visibility: **public**
* This method is **static**.
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $english_operator **mixed**



### findEnglishOperator

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::findEnglishOperator($symbolic_operator)





* Visibility: **public**
* This method is **static**.
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $symbolic_operator **mixed**



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

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::dump($exit, $debug, $callstack_depth)

var_export() the rule with a chained syntax.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $exit **mixed**
* $debug **mixed**
* $callstack_depth **mixed**



### jsonSerialize

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::jsonSerialize()

For implementing JsonSerializable interface.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)




### __toString

    string JClaveau\LogicalFilter\Rule\AbstractRule::__toString()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)




### getInstanceId

    string JClaveau\LogicalFilter\Rule\AbstractRule::getInstanceId()

Returns an id describing the instance internally for debug purpose.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)




### forceLogicalCore

    \JClaveau\LogicalFilter\Rule\OrRule JClaveau\LogicalFilter\Rule\AbstractRule::forceLogicalCore()

Forces the two firsts levels of the tree to be an OrRule having
only AndRules as operands:
['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
As a simplified ruleTree will alwways be reduced to this structure
with no suboperands others than atomic ones or a simpler one like:
['or', ['field', '=', '1'], ['field2', '>', '3']]

This helpes to ease the result of simplify()

* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)



