<?php
namespace JClaveau\LogicalFilter\Rule;

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
     * @return bool true
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}
