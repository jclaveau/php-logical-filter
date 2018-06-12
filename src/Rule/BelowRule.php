<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a < x
 */
class BelowRule extends AbstractAtomicRule
{
    /** @var string operator */
    const operator = '<';

    /** @var scalar $minimum */
    protected $maximum;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can below to.
     */
    public function __construct( $field, $maximum )
    {
        if (    !is_scalar($maximum)
            &&  !$maximum instanceof \DateTimeInterface
            &&  null !== $maximum
        ) {
            throw new \InvalidArgumentException(
                 "Maximum parameter must be a scalar or null "
                ."or implements DateTimeInterface instead of: "
                .var_export($maximum, true)
            );
        }

        $this->field   = $field;
        $this->maximum = $maximum;
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @return bool
     */
    public function hasSolution()
    {
        // if minimum is null, the rule is equivalent to true
        if (is_numeric( $this->maximum )) {
            if (is_nan( $this->maximum ))
                return false;

            if (is_infinite( $this->maximum ) && $this->maximum < 0)
                return false;
        }

        return true;
    }

    /**
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $this->field,
            $debug ? $this->getInstanceId() : self::operator,
            $this->maximum,
        ];
    }

    /**/
}
