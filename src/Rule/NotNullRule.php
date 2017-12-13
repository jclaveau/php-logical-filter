<?php
namespace JClaveau\CustomFilter\Rule;

class NotNullRule extends AbstractAtomicRule
{
    /**
     * @return $this
     */
    public function combineWith( NotNullRule $other_rule )
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
