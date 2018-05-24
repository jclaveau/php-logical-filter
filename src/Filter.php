<?php
namespace JClaveau\CustomFilter;

use JClaveau\CustomFilter\Rule\AbstractOperationRule;
use JClaveau\CustomFilter\Rule\AndRule;
use JClaveau\CustomFilter\Rule\OrRule;
use JClaveau\CustomFilter\Rule\NotRule;

/**
 * addRule
 * resolve
 * hasSolution
 * simplify
 *
 * flatten for OR resolving
 */
class Filter
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

    /** @var  OrRule $optimizedRules */
    // protected $optimizedRules = [];

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
     * Add a constraint for the given field (a list a allowed values).
     * We create an And rule that will gather all the possible combinations
     * of rules that will be applied to the given field.
     *
     * @param string $field
     * @param string $type
     * @param mixed  $value
     *
     * @return $this
     */
    public function addSimpleRule($field, $type, $values)
    {
        $this->rules->addOperand( self::generateSimpleRule(
            $field, $type, $values
        ) );

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
            &&  !in_array('and', $rules_composition)
            &&  !in_array('or',  $rules_composition)
            &&  !in_array('not', $rules_composition)
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
            if ($rules_composition[0] == 'not') {
                $rule = new NotRule();
                $operator = 'not';
            }
            elseif (in_array('and', $rules_composition)) {
                $rule = new AndRule();
                $operator = 'and';
            }
            elseif (in_array('or', $rules_composition)) {
                $rule = new OrRule();
                $operator = 'or';
            }
            else {
                throw new \Exception("Unhandled operation");
            }

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
     * Remove a constraint for the given field (a list a allowed values)
     *
     * $todo absurd?
     *
     * @param string $field
     * @param string $type
     * @param mixed $value
     *
     * @return $this
     * /
    public function removeRule($field, $type, $values)
    {
        if (!isset($this->rules[$field]))
            return $this;

        $ruleClass = self::getRuleClass($type);

        foreach ($this->rules[$field] as $i => $rule) {
            if (!$rule instanceof $ruleClass) {
                continue;
            }

            $rule->removeOperand($values);
        }

        return $this;
    }

    /**
     * Reset all the constraints applied to the given field
     *
     * @todo   check if the ability of removing the rules by "type" is
     *         useful.
     *
     * @param  string $field
     * @param  string $type
     *
     * @return Filter $this
     */
    public function resetRule($field, $type=null)
    {
        if (!isset($this->rules[$field]))
            return $this;

        $ruleClass = self::getRuleClass($type);

        foreach ($this->rules[$field] as $i => $rule) {
            if (!$rule instanceof $ruleClass) {
                continue;
            }

            unset($this->rules[$field][$i]);
        }

        return $this;
    }

    /**
     * Retrieve the rules for a given field
     *
     * @todo simplify / optimize / extract rules related to the given field
     *
     */
    public function getFieldRules($field)
    {


        // if (!isset($this->rules[$field])) {
            // return null;
        // }

        // return $this->rules[$field];
    }

    /**
     * Retrieve all the rules
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Includes all the contraints of an other Filter into the current one.
     */
    public function combineWith( Filter $filterToMerge )
    {
        foreach ($filterToMerge->getRules() as $field => $rules) {
            foreach ($rules as $rule)
                $this->rules[$field][] = $rule;
        }

        return $this;
    }

    /**
     * Remove any constraint being a duplicate of another one.
     *
     */
    public function simplify()
    {
        $this->rules->simplify();

        // combine all combinable constraint

        // compare the result
    }

    /**
     * Returns an array describing the rule tree of the Filter.
     */
    public function toArray()
    {
        return $this->rules->toArray();
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
     * Generates a string id corresponding to the constraints caracterising
     * the filter. This is mainly proposed to simplify cache key generation.
     *
     * @return string The key.
     */
    public function getUid()
    {
        ksort($this->constraints);
        return hash('crc32b', var_export($this->constraints, true));
    }

    /**
     * Removes all the defined constraints.
     *
     * @return $this
     */
    public function flushRules()
    {
        $this->rules = [];
        return $this;
    }

    /**
     * Makes a full copy of the rules property.
     *
     * @return array The copied rules
     */
    private function copyRules()
    {
        $copied_rules = [];

        foreach ($this->rules as $field => $rules) {
            $copied_rules[ $field ] = [];

            foreach ($rules as $i => $rule) {
                $copied_rules[ $field ][$i] = $rule->copy();
            }
        }

        return $copied_rules;
    }

    /**
     * Extracts the keys from the filter and checks that none is unused.
     *
     * @return new Filter instance
     */
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
     * Equivalent of var_dump() but will echo the json_encode value of
     * the native array.
     *
     * This method is useful with the output buffer redirect to a json
     * stream like on Vuble's ajax apis.
     *
     * @return Helper_Table $this
     */
    public function dumpJson($exit=false)
    {
        Debug::dumpJson($this->rules, $exit, 3);
        return $this;
    }

    /**
     * clone the current object.
     *
     * @return Helper_Table A copy of the current instance
     */
    public function copy()
    {
        return clone $this;
    }

    /**/
}
