<?php
namespace JClaveau\CustomFilter;

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

    /** @var  array<OrRule> $rules */
    // protected $optimizedRules = [];

    /**
     */
    public function __construct()
    {
        $this->rules = new Rule\AndRule;
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
    public function addRule($field, $type, $values)
    {
        $ruleClass = self::getRuleClass($type);

        $this->rules->addOperand( new $ruleClass( $field, $values ) );

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
     */
    public function getFieldRules($field)
    {
        if (!isset($this->rules[$field])) {
            return null;
        }

        return $this->rules[$field];
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
        // remove negations
        // $this->rules

        // make combinations due to every OR constraint

        // combine all combinable constraint

        // compare the result

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
