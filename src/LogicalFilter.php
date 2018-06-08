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
            $this->and_( $rules );
    }

    /**
     * This method parses different ways to define the rules of a LogicalFilter.
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
     * @param  string        $operation         and | or
     * @param  array         $rules_description Rules description
     * @return LogicalFilter $this
     */
    protected function addRules( $operation, array $rules_description )
    {
        if (count($rules_description) == 3 && is_string($rules_description[0]) && is_string($rules_description[1])) {
            // Atomic rules
            $new_rule = AbstractRule::generateSimpleRule(
                $rules_description[0], // field
                $rules_description[1], // operator
                $rules_description[2]  // value
            );

            $this->addRule($new_rule, $operation);
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof AbstractRule;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $new_rule) {
                $this->addRule( $new_rule, $operation);
            }
        }
        elseif (count($rules_description) == 1 && is_array($rules_description[0])) {
            $fake_root = new AndRule;

            $this->addCompositeRule_recursion(
                $rules_description[0],
                $fake_root
            );

            $this->addRule( $fake_root->getOperands()[0], $operation );
        }
        else {
            throw new \InvalidArgumentException(
                "Bad set of arguments provided for rules addition: "
                .var_export($rules_description, true)
            );
        }

        return $this;
    }

    /**
     * Add one rule object to the filter
     *
     * @param AbstractRule $rule
     * @param string       $operation
     *
     * @return $this
     */
    protected function addRule( AbstractRule $rule, $operation=AndRule::operator )
    {
        if ( $this->rules instanceof AbstractOperationRule && !$this->rules->getOperands() ) {
            throw new \LogicException(
                 "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have already been simplified: "
                .$this->rules
            );
        }

        if ($this->rules === null) {
            $this->rules = $rule;
        }
        elseif (($tmp_rules = $this->rules) // $this->rules::operator not supported in PHP 5.6
            &&  ($tmp_rules::operator != $operation)) {

            if ($operation == AndRule::operator) {
                $this->rules = new AndRule([$this->rules, $rule]);
            }
            elseif ($operation == OrRule::operator) {
                $this->rules = new OrRule([$this->rules, $rule]);
            }
            else {
                throw new \InvalidArgumentException(
                    "\$operation must be '".AndRule::operator."' or '".OrRule::operator
                    ."' instead of: ".var_export($operation, true)
                );
            }
        }
        else {
            $this->rules->addOperand($rule);
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
            &&  !in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition )
            &&  !in_array( AbstractRule::findSymbolicOperator( OrRule::operator ),  $rules_composition )
            &&  !in_array( AbstractRule::findSymbolicOperator( NotRule::operator ), $rules_composition )
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
            if (    $rules_composition[0] == NotRule::operator
                ||  $rules_composition[0] == AbstractRule::findSymbolicOperator( NotRule::operator ) ) {
                $rule = new NotRule();
            }
            elseif (in_array( AndRule::operator, $rules_composition )
                ||  in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition ) ) {
                $rule = new AndRule();
            }
            elseif (in_array( OrRule::operator, $rules_composition )
                ||  in_array( AbstractRule::findSymbolicOperator( OrRule::operator ), $rules_composition ) ) {
                $rule = new OrRule();
            }
            else {
                throw new \Exception("Unhandled operation");
            }

            $operator = $rule::operator;

            $operands_descriptions = array_filter(
                $rules_composition,
                function ($operand) use ($operator) {
                    return !in_array($operand, [$operator, AbstractRule::findSymbolicOperator( $operator )]);
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
     * This method parses different ways to define the rules of a LogicalFilter
     * and add them as a new And part of the filter.
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
     * @param  mixed The descriptions of the rules to add
     * @return $this
     *
     * @todo remove the _ for PHP 7
     */
    public function and_()
    {
        $this->addRules( AndRule::operator, func_get_args());
        return $this;
    }

    /**
     * This method parses different ways to define the rules of a LogicalFilter
     * and add them as a new Or part of the filter.
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
     * @param  mixed The descriptions of the rules to add
     * @return $this
     *
     * @todo remove the _ for PHP 7
     */
    public function or_()
    {
        $this->addRules( OrRule::operator, func_get_args());
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
        return $copy && $this->rules ? $this->rules->copy() : $this->rules;
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
     * @param  array $options stop_after | stop_before |
     * @return $this
     */
    public function simplify($options=[])
    {
        if ($this->rules) {
            // AndRule added to make all Operation methods available
            $this->rules = (new AndRule([$this->rules]))
                ->simplify( $options )
                // ->dump(true, false)
                ;
        }

        return $this;
    }

    /**
     * Checks if there is at least on set of conditions which is not
     * contradictory.
     *
     * Checking if a filter has solutions require to simplify it first.
     * To let the control on the balance between readability and
     * performances, the required simplification can be saved or not
     * depending on the $save_simplification parameter.
     *
     * @param  $save_simplification
     *
     * @return bool
     */
    public function hasSolution($save_simplification=true)
    {
        if (!$this->rules)
            return true;

        if ($save_simplification) {
            $this->simplify();
            return $this->rules->hasSolution();
        }

        return $this->rules->copy()->simplify()->hasSolution();
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
        return $this->rules ? $this->rules->toArray($debug) : $this->rules;
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
        if ($this->rules) {
            $this->rules = (new AndRule([$this->rules]))
                ->removeNegations()
                // ->dump(true, false)
                ->getOperands()[0];
        }

        return $this;
    }

    /**
     * Remove all OR rules so only one remain at the top of rules tree.
     *
     * This method scans the rule tree recursivelly.
     *
     * @return $this
     */
    public function rootifyDisjunctions()
    {
        if ($this->rules)
            $this->rules = $this->rules->rootifyDisjunctions();

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

        return $newFilter
            ->flushRules()
            ->addRule( $this->rules->copy() );
    }

    /**
     */
    public function dump($exit=false, $debug=false)
    {
        $this->getRules()->dump($exit, $debug, 3);
        return $this;
    }

    /**/
}
