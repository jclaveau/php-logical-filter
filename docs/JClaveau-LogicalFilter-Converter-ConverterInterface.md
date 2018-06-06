JClaveau\LogicalFilter\Converter\ConverterInterface
===============






* Interface name: ConverterInterface
* Namespace: JClaveau\LogicalFilter\Converter
* This is an **interface**






Methods
-------


### onOpenOr

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onOpenOr()

Pseudo-event called while opening a case of the root Or of the
filter.



* Visibility: **public**




### onAndPossibility

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::onAndPossibility($field, $operator, $operand, array $allOperandsByField)

Pseudo-event called while for each And operand of the root Or.

These operands must be only atomic Rules.

* Visibility: **public**


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




### convert

    mixed JClaveau\LogicalFilter\Converter\ConverterInterface::convert(\JClaveau\LogicalFilter\LogicalFilter $filter)





* Visibility: **public**


#### Arguments
* $filter **[JClaveau\LogicalFilter\LogicalFilter](JClaveau-LogicalFilter-LogicalFilter.md)**


