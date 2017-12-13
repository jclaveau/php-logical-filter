<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * a > x
 */
class AboveRule extends AbstractAtomicRule
{
    /** @var scalar $minimum */
    protected $minimum;

    /**
     */
    public function __construct( $minimum )
    {
        if (!is_scalar($minimum)) {
            throw new \InvalidArgumentException(
                "Minimum parameter must be a scalar"
            );
        }

        $this->minimum = $minimum;
    }

    /**
     * @return $this
     */
    public function combineWith( AboveRule $other_rule )
    {
        if ($other_rule->getMinimum() > $this->minimum)
            $this->minimum = $other_rule->getMinimum();

        return $this;
    }

    /**
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return !is_infinite( $this->minimum );
    }

    /**/
}
