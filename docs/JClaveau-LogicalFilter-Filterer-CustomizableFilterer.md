JClaveau\LogicalFilter\Filterer\CustomizableFilterer
===============






* Class name: CustomizableFilterer
* Namespace: JClaveau\LogicalFilter\Filterer
* Parent class: [JClaveau\LogicalFilter\Filterer\Filterer](JClaveau-LogicalFilter-Filterer-Filterer.md)
* This class implements: [JClaveau\LogicalFilter\Filterer\FiltererInterface](JClaveau-LogicalFilter-Filterer-FiltererInterface.md)




Properties
----------


### $rule_validator

    protected mixed $rule_validator





* Visibility: **protected**


Methods
-------


### __construct

    mixed JClaveau\LogicalFilter\Filterer\CustomizableFilterer::__construct(callable $rule_validator)





* Visibility: **public**


#### Arguments
* $rule_validator **callable**



### validateRule

    mixed JClaveau\LogicalFilter\Filterer\FiltererInterface::validateRule($field, $operator, $value, $row, $all_operands)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\FiltererInterface](JClaveau-LogicalFilter-Filterer-FiltererInterface.md)


#### Arguments
* $field **mixed**
* $operator **mixed**
* $value **mixed**
* $row **mixed**
* $all_operands **mixed**



### apply

    mixed JClaveau\LogicalFilter\Filterer\FiltererInterface::apply(\JClaveau\LogicalFilter\LogicalFilter $filter, $data_to_filter)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Filterer\FiltererInterface](JClaveau-LogicalFilter-Filterer-FiltererInterface.md)


#### Arguments
* $filter **[JClaveau\LogicalFilter\LogicalFilter](JClaveau-LogicalFilter-LogicalFilter.md)**
* $data_to_filter **mixed**


