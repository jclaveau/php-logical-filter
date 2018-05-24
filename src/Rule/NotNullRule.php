<?php
namespace JClaveau\LogicalFilter\Rule;

class NotNullRule extends AbstractAtomicRule
{
    /**
     * @param string $field The field that should not be null.
     */
    public function __construct( $field )
    {
        $this->field = $field;
    }

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
