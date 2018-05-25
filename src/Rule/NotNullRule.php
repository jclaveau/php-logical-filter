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
     * @return bool true
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}
