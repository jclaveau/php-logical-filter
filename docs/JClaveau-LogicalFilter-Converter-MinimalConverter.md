JClaveau\LogicalFilter\Converter\MinimalConverter
===============

Basic tools to implements minimal converters: Converters that can
handle simplified filters with or/and/atomic structure.




* Class name: MinimalConverter
* Namespace: JClaveau\LogicalFilter\Converter
* This is an **abstract** class
* This class implements: [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)






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



### onCloseOr

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onCloseOr()

Pseudo-event called while closing a case of the root Or of the
filter.



* Visibility: **public**
* This method is defined by [JClaveau\LogicalFilter\Converter\ConverterInterface](JClaveau-LogicalFilter-Converter-ConverterInterface.md)



