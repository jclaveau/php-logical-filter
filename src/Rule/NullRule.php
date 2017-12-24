<?php
namespace JClaveau\CustomFilter\Rule;

class NullRule extends AbstractAtomicRule
{
    /**
     * @param string $field The field that should be null.
     */
    public function __construct( $field )
    {
        $this->field = $field;
    }

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
