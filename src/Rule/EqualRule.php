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
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, $value )
    {
        if (is_null($value)) {
            throw new \InvalidArgumentException(
                "Value parameter cannot be null, please use NullRule instead"
            );
        }

        $this->field = $field;
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

    /**
     */
    public function toArray()
    {
        return [
            $this->field,
            '=',
            $this->value,
        ];
    }

    /**/
}
