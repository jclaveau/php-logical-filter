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

use       JClaveau\LogicalFilter\Rule\InRule;
use       JClaveau\LogicalFilter\Rule\NotInRule;
use       JClaveau\LogicalFilter\Rule\EqualRule;
use       JClaveau\LogicalFilter\Rule\BelowRule;
use       JClaveau\LogicalFilter\Rule\AboveRule;
use       JClaveau\LogicalFilter\Rule\NotEqualRule;
use       JClaveau\LogicalFilter\Rule\AbstractAtomicRule;
use       JClaveau\LogicalFilter\Rule\AbstractOperationRule;

/**
 * This filterer provides the tools and API to apply a LogicalFilter once it has
 * been simplified.
 */
abstract class Filterer implements FiltererInterface
{
    const leaves_only       = 'leaves_only';
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
    public function onRowMatches(&$row, $key, &$rows, $matching_case, $options)
    {
        if (isset($options[ self::on_row_matches ])) {
            $callback = $options[ self::on_row_matches ];
        }
        elseif (isset($this->custom_actions[ self::on_row_matches ])) {
            $callback = $this->custom_actions[ self::on_row_matches ];
        }
        else {
            return;
        }

        $args = [
            // &$row,
            $row,
            $key,
            &$rows,
            $matching_case,
            $options,
        ];

        call_user_func_array($callback, $args);
    }

    /**
     */
    public function onRowMismatches(&$row, $key, &$rows, $matching_case, $options)
    {
        if (   ! $this->custom_actions
            && ! isset($options[self::on_row_mismatches])
            && ! isset($options[self::on_row_matches])
        ) {
            // Unset by default ONLY if NO custom action defined
            unset($rows[$key]);
            return;
        }

        if (isset($options[ self::on_row_mismatches ])) {
            $callback = $options[ self::on_row_mismatches ];
        }
        elseif (isset($this->custom_actions[ self::on_row_mismatches ])) {
            $callback = $this->custom_actions[ self::on_row_mismatches ];
        }
        else {
            return;
        }

        $args = [
            // &$row,
            $row,
            $key,
            &$rows,
            $matching_case,
            $options,
        ];

        call_user_func_array($callback, $args);
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
     * @param Iterable        $tree_to_filter
     * @param array           $options
     */
    public function apply( LogicalFilter $filter, $tree_to_filter, $options=[] )
    {
        if (! $filter->hasSolution()) {
            return null;
        }

        if (! isset($options['recurse'])) {
            $options['recurse'] = 'before';
        }
        elseif (! in_array($options['recurse'], ['before', 'after', null])) {
            throw new \InvalidArgumentException(
                "Invalid value for 'recurse' option: "
                .var_export($options['recurse'], true)
                ."\nInstead of ['before', 'after', null]"
            );
        }

        return $this->foreachRow(
            !$filter->getRules() ? [] : $filter->addMinimalCase()->getRules()->getOperands(),
            $tree_to_filter,
            $path=[],
            $options
        );
    }

    /**
     */
    protected function foreachRow(array $root_cases, $tree_to_filter, array $path, $options=[])
    {
        // Once the rules are prepared, we parse the data
        foreach ($tree_to_filter as $row_index => $row_to_filter) {
            array_push($path, $row_index);

            if ('before' == $options['recurse']) {
                if ($children = $this->getChildren($row_to_filter)) {
                    $filtered_children = $this->foreachRow(
                        $root_cases,
                        $children,
                        $path,
                        $options
                    );

                    $this->setChildren($row_to_filter, $filtered_children);
                }
            }

            $matching_case = $this->applyOnRow($root_cases, $row_to_filter, $path, $options);

            if ($matching_case) {
                $this->onRowMatches($row_to_filter, $row_index, $tree_to_filter, $matching_case, $options);
            }
            elseif (false === $matching_case) {
                // No case match the rule
                $this->onRowMismatches($row_to_filter, $row_index, $tree_to_filter, $matching_case, $options);
            }
            elseif (null === $matching_case) {
                // We simply avoid rules
                // row out of scope
            }

            if ('after' == $options['recurse']) {
                if ($children = $this->getChildren($row_to_filter)) {
                    $filtered_children = $this->foreachRow(
                        $root_cases,
                        $children,
                        $path,
                        $options
                    );

                    $this->setChildren($row_to_filter, $filtered_children);
                }
            }

            array_pop($path);
        }

        return $tree_to_filter;
    }

    /**
     * @param LogicalFilter   $filter
     * @param Iterable        $tree_to_filter
     * @param array           $options
     *
     * @return bool
     */
    public function hasMatchingCase( LogicalFilter $filter, $row_to_check, $key_to_check, $options=[] )
    {
        if (! $filter->hasSolution()) {
            return null;
        }

        return $this->applyOnRow(
            !$filter->getRules() ? [] : $filter->addMinimalCase()->getRules()->getOperands(),
            $row_to_check,
            $path=[$key_to_check],
            $options
        );
    }

    /**
     */
    protected function applyOnRow(array $root_cases, $row_to_filter, array $path, $options=[])
    {
        $operands_validation_row_cache = [];

        if (! $root_cases) {
            $matching_case = true;
        }
        else {
            $matching_case = null;
            foreach ($root_cases as $and_case_index => $and_case) {
                if (! empty($options['debug'])) {
                    var_dump("Case $and_case_index: ".$and_case);
                }

                $case_is_good = null;
                foreach ($and_case->getOperands() as $i => $rule) {
                    $class = get_class($rule);

                    if (in_array($class, [OrRule::class, AndRule::class, ])) {
                        $field = null;
                        $value = $rule->getOperands();
                    }
                    elseif ($rule instanceof AbstractAtomicRule || ! $rule->isNormalizationAllowed($options)) {
                        $field = $rule->getField();
                        $value = $rule->getValues();
                    }
                    else {
                        throw new \LogicException(
                            "Filtering with a rule which has not been simplified: $rule"
                        );
                    }

                    $operator = $rule::operator;

                    $cache_key = $and_case_index.'~|~'.$field.'~|~'.$operator;

                    if (! empty($operands_validation_row_cache[ $cache_key ])) {
                        $is_valid = $operands_validation_row_cache[ $cache_key ];
                    }
                    else {
                        $is_valid = $this->validateRule(
                            $field,
                            $operator,
                            $value,
                            $row_to_filter,
                            $path,
                            $root_cases,
                            $options
                        );

                        $operands_validation_row_cache[ $cache_key ] = $is_valid;
                    }

                    if (false === $is_valid) {
                        // one of the rules of the and_case do not validate
                        // so all the and_case is invalid
                        $case_is_good = false;
                        break;
                    }
                    elseif (true === $is_valid) {
                        // one of the rules of the and_case do not validate
                        // so all the and_case is invalid
                        $case_is_good = true;
                    }
                }

                if (true === $case_is_good) {
                    // at least one and_case works so we can stop here
                    $matching_case = $and_case;
                    break;
                }
                elseif (false === $case_is_good) {
                    $matching_case = false;
                }
                elseif (null === $case_is_good) {
                    // row out of scope
                }
            }
        }

        return $matching_case;
    }

    /**/
}
