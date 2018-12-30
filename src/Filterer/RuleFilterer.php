<?php
/**
 * RuleFilterer
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\LogicalFilter;
use       JClaveau\LogicalFilter\Rule\EqualRule;
use       JClaveau\LogicalFilter\Rule\BelowRule;
use       JClaveau\LogicalFilter\Rule\AboveRule;
use       JClaveau\LogicalFilter\Rule\OrRule;
use       JClaveau\LogicalFilter\Rule\AndRule;
use       JClaveau\LogicalFilter\Rule\NotRule;
use       JClaveau\LogicalFilter\Rule\NotEqualRule;
use       JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use       JClaveau\LogicalFilter\Rule\AbstractRule;
use       JClaveau\LogicalFilter\Rule\InRule;
use       JClaveau\LogicalFilter\Rule\NotInRule;
use       JClaveau\LogicalFilter\Rule\RegexpRule;

/**
 * This filterer is intended to validate Rules.
 *
 * Manipulating the rules of a logical filter is easier with another one.
 * This filterer is used for the functions of the exposed api like
 * removeRules(), manipulateRules()
 *
 * @todo addRules()
 *
 */
class RuleFilterer extends Filterer
{
    const this        = 'instance';
    const field       = 'field';
    const operator    = 'operator';
    const value       = 'value';
    const depth       = 'depth';
    const children    = 'children';
    const description = 'description';
    const path        = 'path';

    /**
     * @return array
     */
    public function getChildren($row) // strict issue if forcing  AbstractRule with php 5.6 here
    {
        if ($row instanceof InRule) {
            // We do not need to parse the EqualRule operands of InRules
            // as they all share the same field
            return [];
        }

        if ($row instanceof AbstractOperationRule) {
            return $row->getOperands();
        }
    }

    /**
     */
    public function setChildren( &$row, $filtered_children ) // strict issue if forcing  AbstractRule with php 5.6 here
    {
        if ($row instanceof AbstractOperationRule) {
            return $row->setOperandsOrReplaceByOperation( $filtered_children, [] ); // no simplification options?
        }
    }

    /**
     *
     * @return true | false | null
     */
    public function validateRule ($field, $operator, $value, $rule, array $path, $all_operands, $options)
    {
        if (    ! empty($options[ Filterer::leaves_only ])
            && in_array( get_class($rule), [OrRule::class, AndRule::class, NotRule::class] )
        ) {
            // return true;
            return null;
        }

        if (self::field === $field) {
            if ( ! method_exists($rule, 'getField')) {
                // if (in_array( get_class($rule), [AndRule::class, OrRule::class]))
                return null; // The filter cannot be applied to this rule
            }

            try {
                $value_to_compare = $rule->getField();
            }
            catch (\LogicException $e) {
                // This is due to NotInRule.
                // TODO replace it by a TrueRule in this case
                return  null;
            }
        }
        elseif (self::operator === $field) {
            $value_to_compare = $rule::operator;
        }
        elseif (self::value === $field) {
            $description = $rule->toArray();

            if (    3 === count($description)
                && is_string($description[0])
                && is_string($description[1]) ) {
                $value_to_compare = $description[2];
            }
            else {
                return null; // The filter cannot be applied to this rule
            }
        }
        elseif (self::description === $field) {
            $value_to_compare = $rule->toArray();
        }
        elseif (self::depth === $field) {
            // original $depth is lost once the filter is simplified
            throw new \InvalidArgumentException('Depth rule uppport not implemented');
        }
        elseif (self::path === $field) {
            // TODO the description of its parents
            throw new \InvalidArgumentException('Path rule uppport not implemented');
        }
        elseif (self::children === $field) {
            if ( ! method_exists($rule, 'getOperands')) {
                return null; // The filter cannot be applied to this rule
            }
            $value_to_compare = count( $rule->getOperands() );
        }
        else {
            throw new \InvalidArgumentException(
                "Rule filters must belong to ["
                . implode(', ', [
                    self::field,
                    self::operator,
                    self::value,
                    self::depth,
                    self::description,
                    self::children,
                    self::path,
                ])
                ."] contrary to : ".var_export($field, true)
            );
        }

        if (EqualRule::operator === $operator) {
            if (null === $value) {
                $out = is_null($value_to_compare);
            }
            else {
                // TODO support strict comparisons
                $out = $value_to_compare == $value;
            }
        }
        elseif (InRule::operator === $operator) {
            $out = in_array($value_to_compare, $value);
        }
        elseif (BelowRule::operator === $operator) {
            $out = $value_to_compare < $value;
        }
        elseif (AboveRule::operator === $operator) {
            $out = $value_to_compare > $value;
        }
        elseif (RegexpRule::operator === $operator) {
            // TODO support optionnal parameters
            $out = preg_match($value, $value_to_compare);
            if (false === $out) {
                throw new \InvalidArgumentEXception(
                    "Error while calling preg_match( $value ) on: \n"
                    .var_export($value_to_compare, true)
                );
            }
            $out = (bool) $out;
        }
        elseif (NotEqualRule::operator === $operator) {
            if (null === $value) {
                $out = ! is_null($value_to_compare);
            }
            else {
                $out = $value != $value_to_compare;
            }
        }
        elseif (NotInRule::operator === $operator) {
            $out = ! in_array($value_to_compare, $value);
        }
        else {
            throw new \InvalidArgumentException(
                "Unhandled operator: " . $operator
            );
        }

        if ( ! empty($options['debug'])) {
            var_dump(
                "$field, $operator, " . var_export($value, true)
                 . ' ||  '. $value_to_compare . ' => ' . var_export($out, true)
                 . "\n" . get_class($rule)
                 . "\n" . var_export($options, true)
            );
            // $rule->dump();
        }

        return $out;
    }

    /**
     * @param LogicalFilter      $filter
     * @param array|AbstractRule $ruleTree_to_filter
     * @param array              $options leaves_only | debug
     */
    public function apply( LogicalFilter $filter, $ruleTree_to_filter, $options=[] )
    {
        if ( ! $ruleTree_to_filter) {
            return $ruleTree_to_filter;
        }

        if ($ruleTree_to_filter instanceof AbstractRule) {
            $ruleTree_to_filter = [$ruleTree_to_filter];
        }

        if ( ! is_array($ruleTree_to_filter)) {
            throw new \InvalidArgumentException(
                "\$ruleTree_to_filter must be an array or an AbstractRule "
                ."instead of: " . var_export($ruleTree_to_filter, true)
            );
        }

        // Produces "Only variables should be passed by reference" on Travis
        $result = parent::apply($filter, $ruleTree_to_filter, $options);

        return reset( $result );
    }

    /**/
}
