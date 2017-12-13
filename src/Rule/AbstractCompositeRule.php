<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * Composite rules are rules composed of others but are not operations.
 * + In
 * + Between
 * +
 */
abstract class AbstractCompositeRule extends AbstractRule
{
    /**
     * @return $this
     */
    public function combineWith( AbstractCompositeRule $other_rule )
    {
        return $this;
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @todo negative infinite?
     *
     * @return bool
     */
    public final function isAtomic()
    {
        return false;
    }

    /**
     * Transforms all composite rules in the tree of operands into
     * atomic rules.
     *
     * @return array
     */
    public function toAtomicRules()
    {
        throw new \ErrorException('toAtomicRules() MUST be overloaded');
    }

    /**/
}
