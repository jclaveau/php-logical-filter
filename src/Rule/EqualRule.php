<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * a > x
 */
class EqualRule extends AbstractAtomicRule
{
    /** @var mixed $value */
    protected $value;

    /**
     */
    public function __construct( $value )
    {
        if (is_null($value)) {
            throw new \InvalidArgumentException(
                "Value parameter cannot be null, please use NullRule instead"
            );
        }

        $this->value = $value;
    }

    /**
     * @return $this
     */
    public function combineWith( EqualRule $other_rule )
    {
        if ($other_rule->getValue() != $this->minimum)
            $this->value = null;

        return $this;
    }

    /**
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * If the value is null, the combination failed.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return is_null( $this->value );
    }

    /**/
}
