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
 * This filterer provides the tools and API to apply a LogicalFilter once it has
 * been simplified.
 */
abstract class Filterer implements FiltererInterface
{
    const on_row_matches    = 'on_row_matches';
    const on_row_mismatches = 'on_row_mismatches';

    /** @var array $custom_actions */
    protected $custom_actions = [
        // self::on_row_matches    => null,
        // self::on_row_mismatches => null,
    ];

    /**
     */
    public function setCustomActions(array $custom_actions)
    {
        $this->custom_actions = $custom_actions;
        return $this;
    }

    /**
     */
    public function onRowMatches(&$row, $key, &$rows)
    {
        if (isset($this->custom_actions[ self::on_row_matches ])) {
            $args = [
                &$row,
                $key,
                &$rows,
            ];

            call_user_func_array(
                $this->custom_actions[ self::on_row_matches ],
                $args
            );
        }
    }

    /**
     */
    public function onRowMismatches(&$row, $key, &$rows)
    {
        if (!$this->custom_actions) {
            // Unset by default ONLY if NO custom action defined
            unset($rows[$key]);
            return;
        }

        if (isset($this->custom_actions[ self::on_row_mismatches ])) {
            $args = [
                &$row,
                $key,
                &$rows,
            ];
            call_user_func_array(
                $this->custom_actions[ self::on_row_mismatches ],
                $args
            );
        }
    }

    /**
     * @return array
     */
    public function getChildren($row)
    {
        return [];
    }

    /**
     */
    public function setChildren(&$row, $filtered_children)
    {
    }

    /**
     * @param LogicalFilter   $filter
     * @param Iterable|object $tree_to_filter
     * @param array           $options
     */
    public function apply( LogicalFilter $filter, $tree_to_filter, $options=[] )
    {
        $root_OrRule = $filter
            ->simplify(['force_logical_core' => true])
            ->getRules()
            // ->dump(!true)
            ;

        if ($root_OrRule !== null) {
            if (!$root_OrRule->hasSolution())
                return null;

            $root_cases = $root_OrRule->getOperands();
        }
        else {
            $root_cases = [];
        }

        return $this->applyRecursion(
            $root_cases,
            $tree_to_filter,
            $depth=0
        );
    }

    /**
     * @todo use array_filter
     */
    protected function applyRecursion(array $root_cases, $tree_to_filter, $depth)
    {
        // Once the rules are prepared, we parse the data
        foreach ($tree_to_filter as $row_index => $row_to_filter) {
            $operands_validation_row_cache = [];

            if ($children = $this->getChildren($row_to_filter)) {
                $filtered_children = $this->applyRecursion(
                    $root_cases,
                    $children,
                    $depth++
                );

                $this->setChildren($row_to_filter, $filtered_children);
            }

            if (!$root_cases) {
                $matching_case = true;
            }
            else {
                $matching_case = null;
                foreach ($root_cases as $and_case_index => $and_case_description) {

                    $case_is_good = true;
                    foreach ($and_case_description->getOperands() as $i => $rule) {

                        $field = method_exists($rule, 'getField')
                               ? $rule->getField()
                               : null;

                        if ($rule instanceof AbstractOperationRule) {
                            $value = $rule->getOperands();
                        }
                        else {
                            // TODO set a getValue foir every leaf rule
                            $value = $rule->toArray()[2];
                        }

                        $operator = $rule::operator;

                        $cache_key = $and_case_index.'~|~'.$field.'~|~'.$operator;

                        if (!empty($operands_validation_row_cache[ $cache_key ])) {
                            $is_valid = $operands_validation_row_cache[ $cache_key ];
                        }
                        else {
                            $is_valid = $this->validateRule(
                                $field,
                                $operator,
                                $value,
                                $row_to_filter,
                                $depth,
                                $root_cases
                            );

                            $operands_validation_row_cache[ $cache_key ] = $is_valid;
                        }

                        if (!$is_valid) {
                            // one of the rules of the and_case do not validate
                            // so all the and_case is invalid
                            $case_is_good = false;
                            break;
                        }
                    }

                    if ($case_is_good) {
                        // at least one and_case works so we can stop here
                        $matching_case = $and_case_description;
                        break;
                    }
                }
            }

            if ($matching_case) {
                $this->onRowMatches($row_to_filter, $row_index, $tree_to_filter);
            }
            else {
                // No case match the rule
                $this->onRowMismatches($row_to_filter, $row_index, $tree_to_filter);
            }
        }

        return $tree_to_filter;
    }

    /**/
}
