<?php
namespace JClaveau\CustomFilter\Rule;

class NullRule extends AbstractAtomicRule
{
    /**
     * @return $this
     */
    public function combineWith( NullRule $other_rule )
    {
        return $this;
    }

    /**
     * @return bool true
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}
