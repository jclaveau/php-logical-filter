JClaveau\LogicalFilter\Rule\AboveRule
===============

a &gt; x




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


### $ruleAliases

    protected array $ruleAliases = array('!' => 'not', '=' => 'equal', '>' => 'above', '<' => 'below', '><' => 'between', '=><' => 'between_or_equal_lower', '><=' => 'between_or_equal_upper', '=><=' => 'between_or_equal_both', '<=' => 'below_or_equal', '>=' => 'above_or_equal', '!=' => 'not_equal', 'in' => 'in', '!in' => 'not_in')





* Visibility: **protected**
* This property is **static**.


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




### getValues

    array JClaveau\LogicalFilter\Rule\AboveRule::getValues()





* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\Rule\AboveRule::hasSolution()

Checks if the rule do not expect the value to be above infinity.



* Visibility: **public**




### getField

    string JClaveau\LogicalFilter\Rule\AbstractAtomicRule::getField()





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)




### setField

    string JClaveau\LogicalFilter\Rule\AbstractAtomicRule::setField($field)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)


#### Arguments
* $field **mixed**



### renameField

    \JClaveau\LogicalFilter\Rule\AbstractAtomicRule JClaveau\LogicalFilter\Rule\AbstractAtomicRule::renameField($renamings)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)


#### Arguments
* $renamings **mixed**



### toArray

    mixed JClaveau\LogicalFilter\Rule\AbstractAtomicRule::toArray($debug)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractAtomicRule](JClaveau-LogicalFilter-Rule-AbstractAtomicRule.md)


#### Arguments
* $debug **mixed**



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

    string JClaveau\LogicalFilter\Rule\AbstractRule::getRuleClass(string $rule_operator)





* Visibility: **public**
* This method is **static**.
* This method is defined by [JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)


#### Arguments
* $rule_operator **string**



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




### getSemanticId

    string JClaveau\LogicalFilter\Rule\AbstractRule::getSemanticId()

Returns an id corresponding to the meaning of the rule.



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



