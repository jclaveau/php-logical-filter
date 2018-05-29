<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Atomic rules are those who cannot be simplified:
 * + null
 * + not null
 * + equal
 * + above
 * + below
 * Atomic rules are namable.
 */
abstract class AbstractAtomicRule extends AbstractRule
{
    /** @var string $field The field to apply the rule on */
    protected $field;

    /**
     * @return string $field
     */
    public final function getField()
    {
        return $this->field;
    }

    /**
     * @return bool true
     */
    public final function isAtomic()
    {
        return true;
    }

    /**/
}
