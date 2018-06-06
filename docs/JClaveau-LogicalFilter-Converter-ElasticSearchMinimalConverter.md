JClaveau\LogicalFilter\Converter\ElasticSearchMinimalConverter
===============

This class implements a converter for ElasticSearch.




* Class name: ElasticSearchMinimalConverter
* Namespace: JClaveau\LogicalFilter\Converter
* Parent class: [JClaveau\LogicalFilter\Converter\MinimalConverter](JClaveau-LogicalFilter-Converter-MinimalConverter.md)
* This class implements: [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)




Properties
----------


### $output

    protected array $output = array()





* Visibility: **protected**


Methods
-------


### convert

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::convert(\JClaveau\LogicalFilter\LogicalFilter $filter)





* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)


#### Arguments
* $filter **[JClaveau\LogicalFilter\LogicalFilter](JClaveau-LogicalFilter-LogicalFilter.md)**



### onOpenOr

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onOpenOr()

Pseudo-event called while opening a case of the root Or of the
filter.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)




### onCloseOr

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onCloseOr()

Pseudo-event called while closing a case of the root Or of the
filter.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)




### onAndPossibility

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onAndPossibility($field, $operator, $operand, array $allOperandsByField)

Pseudo-event called while for each And operand of the root Or.

These operands must be only atomic Rules.

* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)


#### Arguments
* $field **mixed**
* $operator **mixed**
* $operand **mixed**
* $allOperandsByField **array**



### getLastOrOperandKey

    mixed JClaveau\LogicalFilter\Converter\ElasticSearchMinimalConverter::getLastOrOperandKey()





* Visibility: **protected**




### appendToLastOrOperandKey

    mixed JClaveau\LogicalFilter\Converter\ElasticSearchMinimalConverter::appendToLastOrOperandKey(string $rule)





* Visibility: **protected**


#### Arguments
* $rule **string**


