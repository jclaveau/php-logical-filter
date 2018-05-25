<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Composite rules are rules composed of others but are not operations.
 * + In
 * + Between
 * + Custom rules
 *
 * Composite rules are namable
 */
abstract class AbstractCompositeRule extends AbstractRule
{
    /** @var string $field The field to apply the rule on */
    private $field;

    /**
     * @todo   Factorise with AbstractAtomicRule $field property.
     * @return string $field
     */
    public final function getField()
    {
        return $this->field;
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
