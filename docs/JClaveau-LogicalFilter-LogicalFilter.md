JClaveau\LogicalFilter\LogicalFilter
===============

LogicalFilter describes a set of logical rules structured by
conjunctions and disjunctions (AND and OR).

It's able to simplify them in order to find contractories branches
of the tree rule and check if there is at least one set rules having
possibilities.


* Class name: LogicalFilter
* Namespace: JClaveau\LogicalFilter
* This class implements: JsonSerializable




Properties
----------


### $rules

    protected \JClaveau\LogicalFilter\Rule\AndRule $rules





* Visibility: **protected**


### $default_filterer

    protected \JClaveau\LogicalFilter\Filterer\Filterer $default_filterer





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\LogicalFilter::__construct(array $rules, \JClaveau\LogicalFilter\Filterer\Filterer $default_filterer)

Creates a filter. You can provide a description of rules as in
addRules() as paramater.



* Visibility: **public**


#### Arguments
* $rules **array**
* $default_filterer **[JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)**



### addRules

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::addRules(string $operation, array $rules_description)

This method parses different ways to define the rules of a LogicalFilter.

+ You can add N already instanciated Rules.
+ You can provide 3 arguments: $field, $operator, $value
+ You can provide a tree of rules:
[
     'or',
     [
         'and',
         ['field_5', 'above', 'a'],
         ['field_5', 'below', 'a'],
     ],
     ['field_6', 'equal', 'b'],
 ]

* Visibility: **protected**


#### Arguments
* $operation **string** - &lt;p&gt;and | or&lt;/p&gt;
* $rules_description **array** - &lt;p&gt;Rules description&lt;/p&gt;



### addRule

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::addRule(\JClaveau\LogicalFilter\Rule\AbstractRule $rule, string $operation)

Add one rule object to the filter



* Visibility: **protected**


#### Arguments
* $rule **[JClaveau\LogicalFilter\Rule\AbstractRule](JClaveau-LogicalFilter-Rule-AbstractRule.md)**
* $operation **string**



### addCompositeRule_recursion

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::addCompositeRule_recursion(array $rules_composition, \JClaveau\LogicalFilter\Rule\AbstractOperationRule $recursion_position)

Recursion auxiliary of addCompositeRule.



* Visibility: **protected**


#### Arguments
* $rules_composition **array** - &lt;p&gt;The description of the
                                                 rules to add.&lt;/p&gt;
* $recursion_position **JClaveau\LogicalFilter\Rule\AbstractOperationRule** - &lt;p&gt;The position in the
                                                 tree where rules must
                                                 be added.&lt;/p&gt;



### and_

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::and_()

This method parses different ways to define the rules of a LogicalFilter
and add them as a new And part of the filter.

+ You can add N already instanciated Rules.
+ You can provide 3 arguments: $field, $operator, $value
+ You can provide a tree of rules:
[
     'or',
     [
         'and',
         ['field_5', 'above', 'a'],
         ['field_5', 'below', 'a'],
     ],
     ['field_6', 'equal', 'b'],
 ]

* Visibility: **public**




### or_

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::or_()

This method parses different ways to define the rules of a LogicalFilter
and add them as a new Or part of the filter.

+ You can add N already instanciated Rules.
+ You can provide 3 arguments: $field, $operator, $value
+ You can provide a tree of rules:
[
     'or',
     [
         'and',
         ['field_5', 'above', 'a'],
         ['field_5', 'below', 'a'],
     ],
     ['field_6', 'equal', 'b'],
 ]

* Visibility: **public**




### getRules

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\LogicalFilter::getRules(boolean $copy)

Retrieve all the rules.



* Visibility: **public**


#### Arguments
* $copy **boolean** - &lt;p&gt;By default copy the rule tree to avoid side effects.&lt;/p&gt;



### simplify

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::simplify(array $options)

Remove any constraint being a duplicate of another one.



* Visibility: **public**


#### Arguments
* $options **array** - &lt;p&gt;stop_after | stop_before |&lt;/p&gt;



### hasSolution

    boolean JClaveau\LogicalFilter\LogicalFilter::hasSolution($save_simplification)

Checks if there is at least on set of conditions which is not
contradictory.

Checking if a filter has solutions require to simplify it first.
To let the control on the balance between readability and
performances, the required simplification can be saved or not
depending on the $save_simplification parameter.

* Visibility: **public**


#### Arguments
* $save_simplification **mixed**



### toArray

    array JClaveau\LogicalFilter\LogicalFilter::toArray($debug)

Returns an array describing the rule tree of the Filter.



* Visibility: **public**


#### Arguments
* $debug **mixed** - &lt;p&gt;Provides a source oriented dump.&lt;/p&gt;



### jsonSerialize

    mixed JClaveau\LogicalFilter\LogicalFilter::jsonSerialize()

For implementing JsonSerializable interface.



* Visibility: **public**




### flushRules

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::flushRules()

Removes all the defined rules.



* Visibility: **public**




### renameFields

    string JClaveau\LogicalFilter\LogicalFilter::renameFields($renamings)





* Visibility: **public**


#### Arguments
* $renamings **mixed**



### removeRules

    string JClaveau\LogicalFilter\LogicalFilter::removeRules($filter)





* Visibility: **public**


#### Arguments
* $filter **mixed**



### keepLeafRulesMatching

    array JClaveau\LogicalFilter\LogicalFilter::keepLeafRulesMatching($filter, array $options)





* Visibility: **public**


#### Arguments
* $filter **mixed**
* $options **array**



### listLeafRulesMatching

    array JClaveau\LogicalFilter\LogicalFilter::listLeafRulesMatching($filter)





* Visibility: **public**


#### Arguments
* $filter **mixed**



### onEachRule

    array JClaveau\LogicalFilter\LogicalFilter::onEachRule($filter, $options)





* Visibility: **public**


#### Arguments
* $filter **mixed**
* $options **mixed**



### copy

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::copy()

Clone the current object and its rules.



* Visibility: **public**




### dump

    mixed JClaveau\LogicalFilter\LogicalFilter::dump($exit, $debug, $callstack_depth)





* Visibility: **public**


#### Arguments
* $exit **mixed**
* $debug **mixed**
* $callstack_depth **mixed**



### applyOn

    mixed JClaveau\LogicalFilter\LogicalFilter::applyOn(mixed $data_to_filter, $action_on_matches, \JClaveau\LogicalFilter\Filterer\Filterer|callable|null $filterer)

Applies the current instance to a set of data.



* Visibility: **public**


#### Arguments
* $data_to_filter **mixed**
* $action_on_matches **mixed**
* $filterer **[JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)|callable|null**


