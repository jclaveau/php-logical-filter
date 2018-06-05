<?php
/**
 * ConverterInterface
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 * @version 0.1.0 (29/05/2018)
 */
namespace JClaveau\LogicalFilter\Converter;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 */
interface ConverterInterface
{
    /**
     * Pseudo-event called while opening a case of the root Or of the
     * filter.
     */
    public function onOpenOr();

    /**
     * Pseudo-event called while for each And operand of the root Or.
     * These operands must be only atomic Rules.
     */
    public function onAndPossibility($field, $operator, $operand, array $allOperandsByField);

    /**
     * Pseudo-event called while closing a case of the root Or of the
     * filter.
     */
    public function onCloseOr();

    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter );

    /**/
}
