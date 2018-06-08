JClaveau\LogicalFilter\Rule\EqualRule
===============

a = x




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


### $ruleAliases

    protected array $ruleAliases = array('!' => 'not', '=' => 'equal', '>' => 'above', '<' => 'below', '><' => 'between', '<=' => 'below_or_equal', '>=' => 'above_or_equal', '!=' => 'not_equal', 'in' => 'in', '!in' => 'not_in')





* Visibility: **protected**
* This property is **static**.


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



### hasSolution

    boolean JClaveau\LogicalFilter\Rule\EqualRule::hasSolution()

By default, every atomic rule can have a solution by itself



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



