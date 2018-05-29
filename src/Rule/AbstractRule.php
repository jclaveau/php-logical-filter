<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule implements \JsonSerializable
{
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
     * Clones the rule with a chained syntax.
     *
     * @return Rule A copy of the current instance.
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * var_dump() the rule with a chained syntax.
     */
    public function dump($exit=false)
    {
        $callstack_depth = 2;
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
        $caller = $bt[ $callstack_depth - 2 ];

        echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
        var_export($this->toArray(true));
        echo "\n\n";
        if ($exit)
            exit;
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

    /**/
}
