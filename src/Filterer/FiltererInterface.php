<?php
/**
 * FiltererInterface
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 */
interface FiltererInterface
{
    /**
     * @param LogicalFilter $filter
     * @param Iterable      $data_to_filter
     */
    // public function apply( LogicalFilter $filter, Iterable $dataToFilter )
    public function apply( LogicalFilter $filter, $data_to_filter );

    public function validateRule ($field, $operator, $value, $row, $depth, $all_operands);
    /**/
}
