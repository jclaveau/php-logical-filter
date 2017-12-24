<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * Atomic rules are those who cannot be simplified :
 * + null
 * + not null
 * + equal
 * + above
 * + below
 *
 *
 * Atomic rules are namable
 */
abstract class AbstractAtomicRule extends AbstractRule
{
    /** @var string $field The field to apply the rule on */
    protected $field;

    /**
     * @throws ErrorException If not overloaded
     */
    public function combineWith( AtomicRule $other_rule )
    {
        throw new \ErrorException(
            'This method MUST be overloaded by the inheriting class'
        );
    }

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
