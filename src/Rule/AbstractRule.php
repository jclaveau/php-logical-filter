<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule implements \JsonSerializable
{
    /**
     * If a combination of rules have no possible solution (e.g. a > 10 && a < 5)
     * the all filter won't have any solution in any case. This is useful to
     * stop some process when the filter is not resolvable anymore.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
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
        var_dump($this);
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
