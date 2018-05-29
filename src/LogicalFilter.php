<?php
/**
 * LogicalFilter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 * @version 0.1.0 (29/05/2018)
 */
namespace JClaveau\LogicalFilter;

use JClaveau\LogicalFilter\Rule\AbstractRule;
use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\NotRule;

/**
 * LogicalFilter describes a set of logical rules structured by
 * conjunctions and disjunctions (AND and OR).
 *
 * It's able to simplify them in order to find contractories branches
 * of the tree rule and check if there is at least one set rules having
 * possibilities.
 */
class LogicalFilter implements \JsonSerializable
{
    /** @var  AndRule $rules */
    protected $rules;

    /** @var  array $ruleAliases */
    // protected static $ruleAliases = [
        // '!=' => 'not equal',
        // '='  => 'equal',
        // '>'  => 'above',
        // '>=' => 'above or equal',
        // '<'  => 'below',
        // '<=' => 'below or equal',
    // ];

    /**
     * Creates a filter. You can provide a description of rules as in
     * addRules() as paramater.
     *
     * @param  array $rules
     *
     * @see self::addRules
     */
    public function __construct(array $rules=[])
    {
        if ($rules)
            $this->addRules( $rules );
    }

    /**
     * This method gathers different ways to define the rules of a LogicalFilter.
     * + You can add N already instanciated Rules.
     * + You can provide 3 arguments: $field, $operator, $value
     * + You can provide a tree of rules:
     * [
     *      'or',
     *      [
     *          'and',
     *          ['field_5', 'above', 'a'],
     *          ['field_5', 'below', 'a'],
     *      ],
     *      ['field_6', 'equal', 'b'],
     *  ]
     *
     * @param  mixed         Rules definition
     * @param  array         $rules Rules description
     * @return LogicalFilter $this
     */
    public function addRules()
    {
        if ($this->rules instanceof AndRule && empty($this->rules->getOperands())) {
            // An empty AndRule is a rule having no solution
            throw new \LogicException(
                 "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have been simplified."
            );
        }
        if ($this->rules === null)
            $this->rules = new AndRule;

        $args = func_get_args();

        if (count($args) == 3 && is_string($args[0]) && is_string($args[1])) {
            $newRule = AbstractRule::generateSimpleRule(
                $args[0], // field
                $args[1], // operator
                $args[2]  // value
            );

            $this->rules->addOperand($newRule);
        }
        elseif (count($args) == count(array_filter($args, function($arg) {
            return $arg instanceof AbstractRule;
        })) ) {
            foreach ($args as $i => $newRule) {
                $this->rules->addOperand($newRule);
            }
        }
        elseif (count($args) == 1 && is_array($args[0])) {
            $this->addCompositeRule_recursion(
                $args[0],
                $this->rules
            );
        }
        else {
            throw new \InvalidArgumentException(
                "Bad set of arguments provided for rules addition: "
                .var_export($args, true)
            );
        }

        return $this;
    }

    /**
     * Recursion auxiliary of addCompositeRule.
     *
     * @param array                 $rules_composition  The description of the
     *                                                  rules to add.
     * @param AbstractOperationRule $recursion_position The position in the
     *                                                  tree where rules must
     *                                                  be added.
     *
     * @return $this
     */
    protected function addCompositeRule_recursion(
        array $rules_composition,
        AbstractOperationRule $recursion_position
    ) {
        if (!array_filter($rules_composition, function ($rule_composition_part) {
            return is_string($rule_composition_part);
        })) {
            // at least one operator is required for operation rules
            throw new \InvalidArgumentException(
                "Please provide an operator for the operation: \n"
                .var_export($rules_composition, true)
            );
        }
        elseif (    count($rules_composition) == 3
            &&  !in_array( AndRule::operator, $rules_composition )
            &&  !in_array( OrRule::operator,  $rules_composition )
            &&  !in_array( NotRule::operator, $rules_composition )
        ) {
            // atomic or composit rules
            $operand_left  = $rules_composition[0];
            $operation     = $rules_composition[1];
            $operand_right = $rules_composition[2];

            $rule = AbstractRule::generateSimpleRule(
                $operand_left, $operation, $operand_right
            );
            $recursion_position->addOperand( $rule );
        }
        else {
            // operations
            if ($rules_composition[0] == NotRule::operator) {
                $rule = new NotRule();
            }
            elseif (in_array( AndRule::operator, $rules_composition )) {
                $rule = new AndRule();
            }
            elseif (in_array( OrRule::operator, $rules_composition)) {
                $rule = new OrRule();
            }
            else {
                throw new \Exception("Unhandled operation");
            }

            $operator = $rule::operator;

            $operands_descriptions = array_filter(
                $rules_composition,
                function ($operand) use ($operator) {
                    return $operand !== $operator;
                }
            );

            $remaining_operations = array_filter(
                $operands_descriptions,
                function($operand) {
                    return !is_array($operand);
                }
            );

            if ($remaining_operations) {
                throw new \InvalidArgumentException(
                    "Mixing different operations in the same rule level not implemented: \n"
                    . implode(', ', $remaining_operations)."\n"
                    . 'in ' . var_export($rules_composition, true)
                );
            }

            $recursion_position->addOperand( $rule );

            if ($operator == NotRule::operator && count($operands_descriptions) != 1) {
                throw new \InvalidArgumentException(
                    "Negations can have only one operand: \n"
                    .var_export($rules_composition, true)
                );
            }

            foreach ($operands_descriptions as $operands_description) {
                $this->addCompositeRule_recursion(
                    $operands_description,
                    $rule
                );
            }
        }

        return $this;
    }

    /**
     * Retrieve all the rules.
     *
     * @param  bool $copy By default copy the rule tree to avoid side effects.
     *
     * @return AbstractRule The tree of rules
     */
    public function getRules($copy = true)
    {
        return $copy ? $this->rules->copy() : $this->rules;
    }

    /**
     * Includes all the rules of an other LogicalFilter into the current one.
     *
     * @param  LogicalFilter $filterToCombine
     * @return LogicalFilter $this
     */
    public function combineWith( LogicalFilter $filterToCombine )
    {
        return $this->addRules( $filterToCombine->getRules() );
    }

    /**
     * Remove any constraint being a duplicate of another one.
     *
     * @return $this
     */
    public function simplify()
    {
        $simplified_rules = $this->rules->simplify();
        return $this->flushRules()->addRules( $simplified_rules );
    }

    /**
     * Checks if there is at least on set of conditions which is not
     * contradictory.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return $this->rules->hasSolution();
    }

    /**
     * Returns an array describing the rule tree of the Filter.
     *
     * @param $debug Provides a source oriented dump.
     *
     * @return array A description of the rules.
     */
    public function toArray($debug=false)
    {
        return $this->rules->toArray($debug);
    }

    /**
     * For implementing JsonSerializable interface.
     *
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Replaces every negation operation rules by its opposit not negated
     * one.
     *
     * This method scans the rule tree recursivelly.
     *
     * @return $this
     */
    public function removeNegations()
    {
        $this->rules->removeNegations();
        return $this;
    }

    /**
     * Remove all OR rules so only one remain at the top of rules tree.
     *
     * This method scans the rule tree recursivelly.
     *
     * @return $this
     */
    public function upLiftDisjunctions()
    {
        // We always keep an AndRule as root to be able to add new rules
        // to the Filter afterwards
        $this->rules = new AndRule([$this->rules->upLiftDisjunctions()]);
        return $this;
    }

    /**
     * Removes all the defined rules.
     *
     * @return $this
     */
    public function flushRules()
    {
        $this->rules = null;
        return $this;
    }

    /**
     * Clone the current object and its rules.
     *
     * @return LogicalFilter A copy of the current instance with a copied ruletree
     */
    public function copy()
    {
        $newFilter = clone $this;

        $rules = $this->rules->copy();

        if (    $rules instanceof AndRule
            && count($rules->getOperands()) == 1) {
            // TODO this could be factorized with simplification once its split in little steps
            $rules = $rules->getOperands()[0];
        }

        return $newFilter
            ->flushRules()
            ->addRules( $rules );
    }

    /**/
}
