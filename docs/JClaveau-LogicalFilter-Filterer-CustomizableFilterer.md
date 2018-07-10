JClaveau\LogicalFilter\Filterer\CustomizableFilterer
===============

This filterer provides the tools and API to apply a LogicalFilter once it has
been simplified.




* Class name: CustomizableFilterer
* Namespace: JClaveau\LogicalFilter\Filterer
* Parent class: [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)



Constants
----------


### on_row_matches

    const on_row_matches = 'on_row_matches'





### on_row_mismatches

    const on_row_mismatches = 'on_row_mismatches'





Properties
----------


### $rule_validator

    protected mixed $rule_validator





* Visibility: **protected**


### $custom_actions

    protected array $custom_actions = array()





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Filterer\CustomizableFilterer::__construct(callable $rule_validator)





* Visibility: **public**


#### Arguments
* $rule_validator **callable**



### validateRule

    mixed JClaveau\LogicalFilter\Filterer\FiltererInterface::validateRule($field, $operator, $value, $rule, $depth, $all_operands, $options)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\FiltererInterface](JClaveau-LogicalFilter-Filterer-FiltererInterface.md)


#### Arguments
* $field **mixed**
* $operator **mixed**
* $value **mixed**
* $rule **mixed**
* $depth **mixed**
* $all_operands **mixed**
* $options **mixed**



### setCustomActions

    mixed JClaveau\LogicalFilter\Filterer\Filterer::setCustomActions(array $custom_actions)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $custom_actions **array**



### onRowMatches

    mixed JClaveau\LogicalFilter\Filterer\Filterer::onRowMatches($row, $key, $rows, $matching_case, $options)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $row **mixed**
* $key **mixed**
* $rows **mixed**
* $matching_case **mixed**
* $options **mixed**



### onRowMismatches

    mixed JClaveau\LogicalFilter\Filterer\Filterer::onRowMismatches($row, $key, $rows, $matching_case, $options)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $row **mixed**
* $key **mixed**
* $rows **mixed**
* $matching_case **mixed**
* $options **mixed**



### getChildren

    array JClaveau\LogicalFilter\Filterer\Filterer::getChildren($row)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $row **mixed**



### setChildren

    mixed JClaveau\LogicalFilter\Filterer\Filterer::setChildren($row, $filtered_children)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $row **mixed**
* $filtered_children **mixed**



### apply

    mixed JClaveau\LogicalFilter\Filterer\FiltererInterface::apply(\JClaveau\LogicalFilter\LogicalFilter $filter, $tree_to_filter, $options)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\FiltererInterface](JClaveau-LogicalFilter-Filterer-FiltererInterface.md)


#### Arguments
* $filter **[JClaveau\LogicalFilter\LogicalFilter](JClaveau-LogicalFilter-LogicalFilter.md)**
* $tree_to_filter **mixed**
* $options **mixed**



### applyRecursion

    mixed JClaveau\LogicalFilter\Filterer\Filterer::applyRecursion(array $root_cases, $tree_to_filter, $depth, $options)





* Visibility: **protected**
* This method is defined by [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)


#### Arguments
* $root_cases **array**
* $tree_to_filter **mixed**
* $depth **mixed**
* $options **mixed**


