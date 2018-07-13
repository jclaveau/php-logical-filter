<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule implements \JsonSerializable
{
    /** @var  array $ruleAliases */
    protected static $ruleAliases = [
        '!'    => 'not',
        '='    => 'equal',
        '>'    => 'above',
        '<'    => 'below',
        '><'   => 'between',
        '=><'  => 'between_or_equal_lower',
        '><='  => 'between_or_equal_upper',
        '=><=' => 'between_or_equal_both',
        '<='   => 'below_or_equal',
        '>='   => 'above_or_equal',
        '!='   => 'not_equal',
        'in'   => 'in',
        '!in'  => 'not_in',
    ];

    /**
     */
    public static function findSymbolicOperator($english_operator)
    {
        $association = array_flip( self::$ruleAliases );
        if (isset($association[ $english_operator ]))
            return $association[ $english_operator ];

        return $english_operator;
    }

    /**
     */
    public static function findEnglishOperator($symbolic_operator)
    {
        $association = self::$ruleAliases;
        if (isset($association[ $symbolic_operator ]))
            return $association[ $symbolic_operator ];

        return $symbolic_operator;
    }

    /**
     *
     * @param  string $field
     * @param  string $type
     * @param  mixed  $value
     *
     * @return $this
     */
    public static function generateSimpleRule($field, $type, $values)
    {
        $ruleClass = self::getRuleClass($type);

        return new $ruleClass( $field, $values );
    }

    /**
     * @param  string $rule_operator
     *
     * @return string Class corresponding to the given operator
     */
    public static function getRuleClass($rule_operator)
    {
        $english_rule_operator = self::findEnglishOperator($rule_operator);

        $rule_class = __NAMESPACE__
            . '\\'
            . str_replace('_', '', ucwords($english_rule_operator, '_'))
            . 'Rule';

        if (!class_exists( $rule_class)) {
            throw new \InvalidArgumentException(
                "The class '$rule_class' corresponding to the  operator "
                ."'$rule_operator' / '$english_rule_operator' cannot be found."
            );
        }

        return $rule_class;
    }

    /**
     * Clones the rule with a chained syntax.
     *
     * @return Rule A copy of the current instance.
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * var_export() the rule with a chained syntax.
     */
    public function dump($exit=false, $debug=false, $callstack_depth = 2)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
        $caller = $bt[ $callstack_depth - 2 ];

        echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
        var_export($this->toArray($debug));
        echo "\n\n";
        if ($exit)
            exit;

        return $this;
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
     * @return string
     */
    public function __toString()
    {
        return var_export($this->toArray(), true);
    }

    protected $instance_id;

    /**
     * Returns an id describing the instance internally for debug purpose.
     *
     * @see https://secure.php.net/manual/en/function.spl-object-id.php
     *
     * @return string
     */
    public function getInstanceId()
    {
        if ($this->instance_id)
            return $this->instance_id;

        return $this->instance_id = get_class($this).':'.spl_object_id($this);
    }

    /**
     * Returns an id corresponding to the meaning of the rule.
     *
     * @return string
     */
    public function getSemanticId()
    {
        return hash('crc32b', var_export($this->toArray(), true));
    }

    /**
     * Forces the two firsts levels of the tree to be an OrRule having
     * only AndRules as operands:
     * ['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
     * As a simplified ruleTree will alwways be reduced to this structure
     * with no suboperands others than atomic ones or a simpler one like:
     * ['or', ['field', '=', '1'], ['field2', '>', '3']]
     *
     * This helpes to ease the result of simplify()
     *
     * @return OrRule
     */
    protected function forceLogicalCore()
    {
        if ($this instanceof AbstractAtomicRule) {
            $ruleTree = new OrRule([
                new AndRule([
                    $this
                ])
            ]);
        }
        elseif ($this instanceof AndRule) {
            $ruleTree = new OrRule([
                $this
            ]);
        }
        elseif ($this instanceof OrRule) {
            foreach ($this->operands as $i => $operand) {
                if (!$operand instanceof AndRule)
                    $this->operands[$i] = new AndRule([$operand]);
            }
            $ruleTree = $this;
        }
        elseif ($this instanceof NotEqualRule && $this->getValue() === null) {
            // Non-simplified NotRules are not supported BUT the not of NULL
            $ruleTree = new OrRule([
                new AndRule([
                    $this
                ])
            ]);
        }
        else {
            throw new \LogicException(
                "Unhandled type of simplified rules provided for conversion: "
                .$this
            );
        }

        return $ruleTree;
    }

    /**/
}
