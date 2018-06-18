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
        self::on_row_matches    => null,
        self::on_row_mismatches => null,
    ];

    /**
     */
    public function setCustomActions($action_name, callable $action)
    {
        if (!array_key_exists($action_name, $this->custom_actions)) {
            throw new \InvalidArgumentException(
                "Custom action name must belong to ["
                .implode(', ', array_keys($this->custom_actions) )
                ."] contrary to '$action_name'."
            );
        }

        $this->custom_actions[$action_name] = $action;

        return $this;
    }

    /**
     */
    public function onRowMatches(&$row, $key, &$rows)
    {
        if (isset($this->custom_actions[ self::on_row_matches ])) {
            call_user_func_array(
                $this->custom_actions[ self::on_row_matches ],
                func_get_args()
            );
        }
    }

    /**
     */
    public function onRowMismatches(&$row, $key, &$rows)
    {
        if (isset($this->custom_actions[ self::on_row_mismatches ])) {
            call_user_func_array(
                $this->custom_actions[ self::on_row_mismatches ],
                func_get_args()
            );
        }
        else {
            unset($rows[$key]);
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
            // ->dump(true)
            ;

        if (!$root_OrRule->hasSolution())
            return null;

        $root_cases = $root_OrRule->toArray();
        unset($root_cases[0]);

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

            $one_case_matches = false;

            foreach ($root_cases as $and_case_index => $and_case_description) {
                // The case is an AndRule so we remove the operator
                unset($and_case_description[0]);

                foreach ($and_case_description as $i => $rule_description) {
                    $field    = $rule_description[0];
                    $operator = $rule_description[1];
                    $value    = $rule_description[2];

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

                    // var_dump("$field $operator " . var_export($value, true) ." => ". var_export($is_valid, true));
                    if (!$is_valid) {
                        // one of the rules of the and_case do not validate
                        // so all the and_case is invalid
                        continue 2;
                    }
                }

                $one_case_matches = true;
                // at least one and_case works so we can stop here
                break;
            }

            if ($one_case_matches) {
                // at least 1 case matches the
                if ($children = $this->getChildren($row_to_filter)) {
                    $filtered_children = $this->applyRecursion(
                        $root_cases,
                        $children,
                        $depth++
                    );
                    $this->setChildren($row_to_filter, $filtered_children);
                }

                $this->onRowMatches($row_to_filter, $row_index, $tree_to_filter);
            }
            else {
                // No case match the rule
                // $row_to_filter->dump(!true);
                // var_dump($tree_to_filter);
                $this->onRowMismatches($row_to_filter, $row_index, $tree_to_filter);
            }
        }

        return $tree_to_filter;
    }

    /**/
}
