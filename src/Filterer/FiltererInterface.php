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
    public function apply( LogicalFilter $filter, $tree_to_filter, $options=[] );

    public function validateRule ($field, $operator, $value, $rule, $depth, $all_operands, $options);

    /**/
}
