<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a = x
 */
class EqualRule extends AbstractAtomicRule
{
    /** @var string operator */
    const operator = '=';

    /** @var mixed $value */
    protected $value;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, $value )
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getValue();
    }

    /**
     * By default, every atomic rule can have a solution by itself
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}
