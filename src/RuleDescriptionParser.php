<?php
/**
 * LogicalFilter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter;

use JClaveau\LogicalFilter\Rule\AbstractRule;
use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\NotRule;

/**
 * This "parser" (lexer?) generates a tree of rule instances from a
 * description of rules which can be a composed of builtin types, based
 * on a tree of arrays mixed with segments of rule tree or LogicalFilter
 * instances.
 */
// abstract class RuleDescriptionParser
class RuleDescriptionParser
{
    /**
     * This method parses different ways to define the rules of a LogicalFilter.
     * + You can add N already instanciated Rules.
     * + You can provide 3 arguments: $field, $operator, $value
     * + You can provide a tree of rules:
     * ['or',
     *      ['and',
     *          ['field_5', 'above', 'a'],
     *          ['field_5', 'below', 'a'],
     *      ],
     *      ['field_6', 'equal', 'b'],
     *  ]
     *
     * @param  string            $operation         and | or
     * @param  array             $rules_description Rules description
     * @param  AbstractRule|null $filter_rules The existing ruletree of the filter
     * @param  array             $options
     *
     * @return AbstractRule  The updated rule tree
     */
    public static function updateRuleTreeFromDescription(
        $operation,
        array $rules_description,
        $filter_rules,
        $options
    ) {
        if ($rules_description == [null]) {
            // TODO this is due to the bad design of using "Null" instead of
            // TrueRule when a Filter "has no rule". So it's the equivalent of
            // "and true" or "or true".
            // Remove it while fixing https://github.com/jclaveau/php-logical-filter/issues/59
            if (AndRule::operator == $operation) {
                // A && True <=> A
                return $filter_rules;
            }
            elseif (OrRule::operator == $operation) {
                // A || True <=> True
                // TODO replace by true or TrueRule
                $filter_rules = null;
                return $filter_rules;
            }
            else {
                throw new InvalidArgumentException(
                    "Unhandled operation '$operation'"
                );
            }
        }

        if (   3 == count($rules_description)
            && is_string($rules_description[0])
            && is_string($rules_description[1])
        ) {
            // Atomic rules
            $new_rule = AbstractRule::generateSimpleRule(
                $rules_description[0], // field
                $rules_description[1], // operator
                $rules_description[2], // value
                $options
            );

            $filter_rules = static::addRule($new_rule, $operation, $filter_rules);
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof LogicalFilter;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $filter_inside_description) {
                $rules = $filter_inside_description->getRules();
                if (null !== $rules) {
                    $filter_rules = static::addRule($rules, $operation, $filter_rules);
                }
            }
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof AbstractRule;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $new_rule) {
                $filter_rules = static::addRule($new_rule, $operation, $filter_rules);
            }
        }
        elseif (1 == count($rules_description) && is_array($rules_description[0])) {
            if (count($rules_description[0]) == count(array_filter($rules_description[0], function($arg) {
                return $arg instanceof AbstractRule;
            })) ) {
                // Case of $filter->or_([AbstractRule, AbstractRule, AbstractRule, ...])
                foreach ($rules_description[0] as $i => $new_rule) {
                    $filter_rules = static::addRule($new_rule, $operation, $filter_rules);
                }
            }
            else {
                $fake_root = new AndRule;

                static::addCompositeRule_recursion(
                    $rules_description[0],
                    $fake_root,
                    $options
                );

                $filter_rules = static::addRule($fake_root->getOperands()[0], $operation, $filter_rules);
            }
        }
        else {
            throw new \InvalidArgumentException(
                "Bad set of arguments provided for rules addition: "
                .var_export($rules_description, true)
            );
        }

        return $filter_rules;
    }

    /**
     * Add one rule object to the filter
     *
     * @param AbstractRule $rule
     * @param string       $operation
     *
     * @return $filter_rules The updated rule tree of the filter
     */
    protected static function addRule( AbstractRule $rule, $operation=AndRule::operator, AbstractRule $filter_rules=null)
    {

        if ($filter_rules && in_array( get_class($filter_rules), [AndRule::class, OrRule::class])
            && ! $filter_rules->getOperands()) {
            throw new \LogicException(
                 "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have already been simplified: "
                .$filter_rules
            );
        }

        if (null === $filter_rules) {
            return $rule;
        }
        elseif (($tmp_rules = $filter_rules) // $this->rules::operator not supported in PHP 5.6
            && ($tmp_rules::operator != $operation)
        ) {
            if (AndRule::operator == $operation) {
                return new AndRule([$filter_rules, $rule]);
            }
            elseif (OrRule::operator == $operation) {
                return new OrRule([$filter_rules, $rule]);
            }
            else {
                throw new \InvalidArgumentException(
                    "\$operation must be '".AndRule::operator."' or '".OrRule::operator
                    ."' instead of: ".var_export($operation, true)
                );
            }
        }
        else {
            $filter_rules->addOperand($rule);
        }

        return $filter_rules;
    }

    /**
     * Recursion auxiliary of addCompositeRule.
     *
     * @param array                 $rules_composition  The description of the
     *                                                  rules to add.
     * @param AbstractOperationRule $recursion_position The position in the
     *                                                  tree where rules must
     *                                                  be added.
     */
    protected static  function addCompositeRule_recursion(
        array $rules_composition,
        AbstractOperationRule $recursion_position,
        $options
    ) {
        if (! array_filter($rules_composition, function ($rule_composition_part) {
            return is_string($rule_composition_part);
        })) {
            // at least one operator is required for operation rules
            throw new \InvalidArgumentException(
                "Please provide an operator for the operation: \n"
                .var_export($rules_composition, true)
            );
        }
        elseif ( 3 == count($rules_composition)
            && AbstractRule::isLeftOperand($rules_composition[0])
            && AbstractRule::isOperator($rules_composition[1])
        ) {
            // atomic or composit rules
            $operand_left  = $rules_composition[0];
            $operation     = $rules_composition[1];
            $operand_right = $rules_composition[2];

            $rule = AbstractRule::generateSimpleRule(
                $operand_left, $operation, $operand_right, $options
            );
            $recursion_position->addOperand($rule);
        }
        else {
            // operations
            if (   NotRule::operator == $rules_composition[0]
                || $rules_composition[0] == AbstractRule::findSymbolicOperator( NotRule::operator ) ) {
                $rule = new NotRule();
            }
            elseif (in_array( AndRule::operator, $rules_composition )
                || in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition)) {
                $rule = new AndRule();
            }
            elseif (in_array( OrRule::operator, $rules_composition )
                || in_array( AbstractRule::findSymbolicOperator( OrRule::operator ), $rules_composition) ) {
                $rule = new OrRule();
            }
            else {
                throw new \InvalidArgumentException(
                    "A rule description seems to be an operation but do "
                    ."not contains a valid operator: ".var_export($rules_composition, true)
                );
            }

            $operator = $rule::operator;

            $operands_descriptions = array_filter(
                $rules_composition,
                function ($operand) use ($operator) {
                    return ! in_array($operand, [$operator, AbstractRule::findSymbolicOperator($operator)]);
                }
            );

            $non_true_rule_descriptions = array_filter(
                $operands_descriptions,
                function($operand) {
                    return null !== $operand  // no rule <=> true
                        || true !== $operand
                        ;
                }
            );

            foreach ($operands_descriptions as $i => $operands_description) {
                if (false === $operands_description) {
                    $operands_descriptions[ $i ] = ['and']; // FalseRule hack
                }
                elseif (null === $operands_description || true === $operands_description) {
                    $operands_description = ['and'];
                    if (empty($non_true_rule_descriptions)) {
                        throw new \LogicException(
                            "TrueRules are not implemented. Please add "
                            ."them to operations having other type of rules"
                        );
                    }

                    unset($operands_descriptions[ $i ]);
                }
            }

            $remaining_operations = array_filter(
                $operands_descriptions,
                function($operand) {
                    return ! is_array($operand)
                        && ! $operand instanceof AbstractRule
                        && ! $operand instanceof LogicalFilter
                        ;
                }
            );

            if (! empty($remaining_operations)) {
                throw new \InvalidArgumentException(
                    "Mixing different operations in the same rule level not implemented: \n["
                    . implode(', ', $remaining_operations)."]\n"
                    . 'in ' . var_export($rules_composition, true)
                );
            }

            if (NotRule::operator == $operator && 1 != count($operands_descriptions)) {
                throw new \InvalidArgumentException(
                    "Negations can have only one operand: \n"
                    .var_export($rules_composition, true)
                );
            }

            foreach ($operands_descriptions as $operands_description) {
                if ($operands_description instanceof AbstractRule) {
                    $rule->addOperand($operands_description);
                }
                elseif ($operands_description instanceof LogicalFilter) {
                    $rule->addOperand($operands_description->getRules());
                }
                else {
                    static::addCompositeRule_recursion(
                        $operands_description,
                        $rule,
                        $options
                    );
                }
            }

            $recursion_position->addOperand($rule);
        }
    }

    /**/
}
