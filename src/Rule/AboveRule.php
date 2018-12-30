<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a > x
 */
class AboveRule extends AbstractAtomicRule
{
    /** @var string operator */
    const operator = '>';

    /** @var scalar $minimum */
    protected $minimum;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can above to.
     */
    public function __construct( $field, $minimum )
    {
        if (   ! is_scalar($minimum)
            && ! $minimum instanceof \DateTimeInterface
            && null !== $minimum
        ) {
            throw new \InvalidArgumentException(
                "Minimum parameter must be a scalar or null "
                ."or implements DateTimeInterface instead of: "
                .var_export($minimum, true)
            );
        }

        $this->field   = $field;
        $this->minimum = $minimum;
    }

    /**
     * @deprecated getLowerLimit
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     */
    public function getLowerLimit()
    {
        return $this->minimum;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getMinimum();
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @return bool
     */
    public function hasSolution(array $simplification_options=[])
    {
        // if minimum is null, the rule is equivalent to true
        if (is_numeric( $this->minimum )) {
            if (is_nan( $this->minimum )) {
                return false;
            }

            if (is_infinite( $this->minimum ) && $this->minimum > 0) {
                return false;
            }
        }

        return true;
    }

    /**/
}
