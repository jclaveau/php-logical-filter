JClaveau\LogicalFilter\Rule\AbstractRule
===============






* Class name: AbstractRule
* Namespace: JClaveau\LogicalFilter\Rule
* This is an **abstract** class
* This class implements: JsonSerializable




Properties
----------


### $ruleAliases

    protected array $ruleAliases = array('!' => 'not', '=' => 'equal', '>' => 'above', '<' => 'below', '><' => 'between', '=><' => 'between_or_equal_lower', '><=' => 'between_or_equal_upper', '=><=' => 'between_or_equal_both', '<=' => 'below_or_equal', '>=' => 'above_or_equal', '!=' => 'not_equal', 'in' => 'in', '!in' => 'not_in')





* Visibility: **protected**
* This property is **static**.


Methods
-------


### findSymbolicOperator

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::findSymbolicOperator($english_operator)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $english_operator **mixed**



### findEnglishOperator

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::findEnglishOperator($symbolic_operator)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $symbolic_operator **mixed**



### generateSimpleRule

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\Rule\AbstractRule::generateSimpleRule(string $field, string $type, $values)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $field **string**
* $type **string**
* $values **mixed**



### getRuleClass

    string JClaveau\LogicalFilter\Rule\AbstractRule::getRuleClass(string $rule_operator)





* Visibility: **public**
* This method is **static**.


#### Arguments
* $rule_operator **string**



### copy

    \JClaveau\LogicalFilter\Rule\Rule JClaveau\LogicalFilter\Rule\AbstractRule::copy()

Clones the rule with a chained syntax.



* Visibility: **public**




### dump

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::dump($exit, $debug, $callstack_depth)

var_export() the rule with a chained syntax.



* Visibility: **public**


#### Arguments
* $exit **mixed**
* $debug **mixed**
* $callstack_depth **mixed**



### jsonSerialize

    mixed JClaveau\LogicalFilter\Rule\AbstractRule::jsonSerialize()

For implementing JsonSerializable interface.



* Visibility: **public**




### __toString

    string JClaveau\LogicalFilter\Rule\AbstractRule::__toString()





* Visibility: **public**




### getInstanceId

    string JClaveau\LogicalFilter\Rule\AbstractRule::getInstanceId()

Returns an id describing the instance internally for debug purpose.



* Visibility: **public**




### getSemanticId

    string JClaveau\LogicalFilter\Rule\AbstractRule::getSemanticId()

Returns an id corresponding to the meaning of the rule.



* Visibility: **public**




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



