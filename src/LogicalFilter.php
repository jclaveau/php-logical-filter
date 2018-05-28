<?php
namespace JClaveau\LogicalFilter;

use JClaveau\LogicalFilter\Rule\AbstractRule;
use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\NotRule;

/**
 */
class LogicalFilter implements \JsonSerializable
{
    /** @var  AndRule $rules */
    protected $rules;

    /** @var  array $ruleAliases */
    protected static $ruleAliases = [
        '!=' => 'not equal',
        '='  => 'equal',
        '>'  => 'above',
        '>=' => 'above or equal',
        '<'  => 'below',
        '<=' => 'below or equal',
    ];

    /**
     */
    public function __construct()
    {
        $this->rules = new AndRule;
    }

    /**
     * @param  string rule name
     *
     * @return string corresponding rule class name
     */
    public static function getRuleClass($rule_type)
    {
        $rule_class = __NAMESPACE__
            . '\\Rule\\'
            . str_replace('_', '', ucwords($rule_type, '_'))
            . 'Rule';

        if (!class_exists( $rule_class)) {
            throw new \InvalidArgumentException(
                "No rule class corresponding to the expected type: '$rule_type'. "
                ."Looking for '$rule_class'"
            );
        }

        return $rule_class;
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
     * @return LogicalFilter $this
     */
    public function addRules()
    {
        $args = func_get_args();

        if (count($args) == 3 && is_string($args[0]) && is_string($args[1])) {
            $newRule = self::generateSimpleRule(
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
     *
     * @param string $field
     * @param string $type
     * @param mixed  $value
     *
     * @return $this
     */
    public static function generateSimpleRule($field, $type, $values)
    {
        $ruleClass = self::getRuleClass($type);

        return new $ruleClass( $field, $values );
    }

    /**
     * Transforms an array gathering different rules representing
     * atomic and operation rules into a tree of Rules added to the
     * current Filter.
     *
     * @param array $rules_composition
     *
     * @return $this
     */
    public function addCompositeRule(array $rules_composition)
    {
        $this->addCompositeRule_recursion(
            $rules_composition,
            $this->rules
        );

        return $this;
    }

    /**
     * Recursion auxiliary of addCompositeRule.
     *
     * @param array $rules_composition
     *
     * @return $this
     */
    private function addCompositeRule_recursion(
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

            $rule = self::generateSimpleRule(
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

            if ($operator == 'not' && count($operands_descriptions) != 1) {
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
     * Removes all the defined constraints.
     *
     * @return $this
     */
    public function flushRules()
    {
        $this->rules = new AndRule;
        return $this;
    }

    /**
     * Extracts the keys from the filter and checks that none is unused.
     *
     * @return new Filter instance
     * /
    public function useAllRules(array $rules_to_use)
    {
        $rules = $this->copyRules();

        $parameters = [];

        foreach ($rules_to_use as $parameter_name => $rule_to_use) {

            // TODO simplify $rule_to_use to have only one set of parameters
            //


            if (isset( $rules[ $rule_to_use[0] ][ $rule_to_use[1] ] )) {

                $parameters[ $parameter_name ]
                    = $rules[ $rule_to_use[0] ][ $rule_to_use[1] ]->getParameters();

                unset($rules[ $rule_to_use[0] ][ $rule_to_use[1] ]);
            }
        }

        if (array_filter($rules)) {
            throw new \ErrorException("Unused rules in the filter: "
                .print_r($rules, true));
        }

        return $parameters;
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
