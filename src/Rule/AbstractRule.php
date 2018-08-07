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

    protected static $static_cache = [
        'rules_generation' => [],
    ];

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
        $cache_key = hash('crc32b', serialize( func_get_args()) );
        if (isset(self::$static_cache['rules_generation'][$cache_key]))
            return self::$static_cache['rules_generation'][$cache_key]->copy();

        $ruleClass = self::getRuleClass($type);

        return self::$static_cache['rules_generation'][$cache_key] = new $ruleClass( $field, $values );
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
     * Dumps the rule with a chained syntax.
     *
     * @param bool  $exit=false
     * @param array $options    + callstack_depth=2 The level of the caller to dump
     *                          + mode='string' in 'export' | 'dump' | 'string' | 'xdebug'
     *
     * @return $this
     */
    public final function dump($exit=false, array $options=[])
    {
        $default_options = [
            'callstack_depth' => 2,
            'mode'            => 'string',
            // 'show_instance'   => false,
        ];
        foreach ($default_options as $default_option => &$default_value) {
            if (!isset($options[ $default_option ]))
                $options[ $default_option ] = $default_value;
        }
        extract($options);

        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
        $caller = $bt[ $callstack_depth - 2 ];

        echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
        if ($mode == 'string') {
            if ( ! isset($options['indent_unit']))
                $options['indent_unit'] = "    ";

            echo ($this->toString($options));
        }
        elseif ($mode == 'dump') {
            if ($xdebug_enabled = ini_get('xdebug.overload_var_dump'))
                ini_set('xdebug.overload_var_dump', 0);

            var_dump($this->toArray($options));

            if ($xdebug_enabled)
                ini_set('xdebug.overload_var_dump', 1);
        }
        elseif ($mode == 'xdebug') {
            if ( ! function_exists('xdebug_is_enabled')) {
                throw new \RuntimeException("Xdebug is not installed");
            }
            if ( ! xdebug_is_enabled()) {
                throw new \RuntimeException("Xdebug is disabled");
            }

            if ($xdebug_enabled = ini_get('xdebug.overload_var_dump'))
                ini_set('xdebug.overload_var_dump', 1);

            var_dump($this->toArray($options));

            if ($xdebug_enabled)
                ini_set('xdebug.overload_var_dump', 0);
        }
        elseif ($mode == 'export') {
            var_export($this->toArray($options));
        }
        else {
            throw new \InvalidArgumentException(
                 "'mode' option must belong to ['string', 'export', 'dump'] "
                ."instead of " . var_export($mode, true)
            );
        }
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
        return $this->toString();
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
        return hash('crc32b', serialize( $this->toArray() ));
        // return hash('md4', serialize( $this->toArray() ));
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
