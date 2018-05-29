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


### $ruleAliases

    protected array $ruleAliases = array('!=' => 'not equal', '=' => 'equal', '>' => 'above', '>=' => 'above or equal', '<' => 'below', '<=' => 'below or equal')





* Visibility: **protected**
* This property is **static**.


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\LogicalFilter::__construct(array $rules)

Creates a filter. You can provide a description of rules as in
addRules() as paramater.



* Visibility: **public**


#### Arguments
* $rules **array**



### addRules

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::addRules(array $rules)

This method gathers different ways to define the rules of a LogicalFilter.

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


#### Arguments
* $rules **array** - &lt;p&gt;Rules description&lt;/p&gt;



### addCompositeRule_recursion

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::addCompositeRule_recursion(array $rules_composition, \JClaveau\LogicalFilter\Rule\AbstractOperationRule $recursion_position)

Recursion auxiliary of addCompositeRule.



* Visibility: **protected**


#### Arguments
* $rules_composition **array** - &lt;p&gt;The description of the
                                                 rules to add.&lt;/p&gt;
* $recursion_position **[JClaveau\LogicalFilter\Rule\AbstractOperationRule](JClaveau-LogicalFilter-Rule-AbstractOperationRule.md)** - &lt;p&gt;The position in the
                                                 tree where rules must
                                                 be added.&lt;/p&gt;



### getRules

    \JClaveau\LogicalFilter\Rule\AbstractRule JClaveau\LogicalFilter\LogicalFilter::getRules(boolean $copy)

Retrieve all the rules.



* Visibility: **public**


#### Arguments
* $copy **boolean** - &lt;p&gt;By default copy the rule tree to avoid side effects.&lt;/p&gt;



### combineWith

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::combineWith(\JClaveau\LogicalFilter\LogicalFilter $filterToCombine)

Includes all the rules of an other LogicalFilter into the current one.



* Visibility: **public**


#### Arguments
* $filterToCombine **[JClaveau\LogicalFilter\LogicalFilter](JClaveau-LogicalFilter-LogicalFilter.md)**



### simplify

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::simplify()

Remove any constraint being a duplicate of another one.



* Visibility: **public**




### hasSolution

    boolean JClaveau\LogicalFilter\LogicalFilter::hasSolution()

Checks if there is at least on set of conditions which is not
contradictory.



* Visibility: **public**




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




### removeNegations

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::removeNegations()

Replaces every negation operation rules by its opposit not negated
one.

This method scans the rule tree recursivelly.

* Visibility: **public**




### upLiftDisjunctions

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::upLiftDisjunctions()

Remove all OR rules so only one remain at the top of rules tree.

This method scans the rule tree recursivelly.

* Visibility: **public**




### flushRules

    \JClaveau\LogicalFilter\LogicalFilter JClaveau\LogicalFilter\LogicalFilter::flushRules()

Removes all the defined constraints.



* Visibility: **public**




### copy

    \JClaveau\LogicalFilter\new JClaveau\LogicalFilter\LogicalFilter::copy()

Extracts the keys from the filter and checks that none is unused.



* Visibility: **public**



