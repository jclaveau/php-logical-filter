<?php
/**
 * Filterer
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\Filterer\FiltererInterface;
use       JClaveau\LogicalFilter\LogicalFilter;

use       JClaveau\LogicalFilter\Rule\EqualRule;
use       JClaveau\LogicalFilter\Rule\BelowRule;
use       JClaveau\LogicalFilter\Rule\AboveRule;
use       JClaveau\LogicalFilter\Rule\NotEqualRule;

/**
 */
abstract class Filterer
{
    /**
     * @param LogicalFilter $filter
     */
    public function apply( LogicalFilter $filter, $data_to_filter )
    {
        if (!is_iterable($data_to_filter)) {
            throw new \InvalidArgumentException(
                "The data to filter must be an iterable"
            );
        }

        $root_OrRule = $filter->simplify(['force_logical_core' => true])->getRules();

        if (!$root_OrRule->hasSolution())
            return null;

        $root_cases = [];
        foreach ($root_OrRule->getOperands() as $andOperand) {

            $operands_by_fields = $andOperand->groupOperandsByFieldAndOperator();

            foreach ($operands_by_fields as $field => $operands_by_operator) {
                foreach ($operands_by_operator as $operator => $operands_of_operator) {
                    if (count($operands_of_operator) != 1) {
                        throw new \RuntimeException(
                             "Once a logical filter is simplified, there MUST be "
                            ."no more than one operand by operator instead of for '$field' / '$operator': "
                            .var_export($operands_of_operator, true)
                        );
                    }

                    $operand = reset($operands_of_operator);

                    if ($operand instanceof EqualRule) {
                        $operands_by_fields[ $field ][ $operator ] = $operand->getValue();
                    }
                    elseif ($operand instanceof AboveRule) {
                        $operands_by_fields[ $field ][ $operator ] = $operand->getMinimum();
                    }
                    elseif ($operand instanceof BelowRule) {
                        $operands_by_fields[ $field ][ $operator ] = $operand->getMaximum();
                    }
                    elseif ($operand instanceof NotEqualRule) {
                        if (null !== $operands_by_fields[ $field ][ $operator ] = $operand->getValue()) {
                            throw new \ErrorException(
                                "NotEqualRule is only used for nullk values after simplification"
                            );
                        }
                    }
                }
            }

            $root_cases[] = $operands_by_fields;
        }

        // Once the rules are prepared, we parse the data
        foreach ($data_to_filter as $row_index => $row_to_filter) {
            $operands_validation_row_cache = [];

            $root_cases_validity_count = count($root_cases);

            foreach ($root_cases as $case_index => $operands_by_fields) {
                foreach ($operands_by_fields as $field => $operands_by_operator) {
                    foreach ($operands_by_operator as $operator => $value) {

                        $cache_key = $case_index.'~|~'.$field.'~|~'.$operator;

                        if (!empty($operands_validation_row_cache[ $cache_key ]))
                            return $operands_validation_row_cache[ $cache_key ];

                        $is_valid = $this->validateRule(
                            $field,
                            $operator,
                            $value,
                            $row_to_filter,
                            $operands_by_fields
                        );

                        if (!$is_valid) {
                            $root_cases_validity_count--;
                            continue 3;
                        }

                        $operands_validation_row_cache[ $cache_key ] = $is_valid;
                    }
                }
            }

            if ( ! $root_cases_validity_count)
                unset($data_to_filter[$row_index]);
        }

        return $data_to_filter;
    }

    /**/
}