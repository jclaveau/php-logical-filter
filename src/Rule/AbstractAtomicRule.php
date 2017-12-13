<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * Atomic rules are those who cannot be simplified :
 * + null
 * + not null
 * + equal
 * + above
 * + below
 */
abstract class AbstractAtomicRule extends AbstractRule
{
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
     * @return bool true
     */
    public final function isAtomic()
    {
        return true;
    }

    /**/
}
