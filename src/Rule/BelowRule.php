<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * a < x
 */
class BelowRule extends AbstractAtomicRule
{
    /** @var scalar $minimum */
    protected $maximum;

    /**
     */
    public function __construct( $maximum )
    {
        if (!is_scalar($maximum)) {
            throw new \InvalidArgumentException(
                "Maximum parameter must be a scalar"
            );
        }

        $this->maximum = $maximum;
    }

    /**
     * @return $this
     */
    public function combineWith( BelowRule $other_rule )
    {
        if ($other_rule->getMaximum() < $this->limit)
            $this->maximum = $other_rule->getMaximum();

        return $this;
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return !is_infinite( $this->maximum );
    }

    /**
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**/
}
