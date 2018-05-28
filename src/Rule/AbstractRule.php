<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule implements \JsonSerializable
{
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
