<?php
namespace JClaveau\LogicalFilter;

class BetweenRule extends Rule
{
    /** @var mixed $maximum */
    protected $maximum;

    /** @var mixed $minimum */
    protected $minimum;

    /**
     */
    public function __construct( $maximum, $minimum )
    {
        if (!is_scalar($minimum)) {
            throw new \InvalidArgumentException(
                "Minimum parameter must be a scalar"
            );
        }

        if (!is_scalar($maximum)) {
            throw new \InvalidArgumentException(
                "Maximum parameter must be a scalar"
            );
        }

        $this->maximum = $maximum;
        $this->minimum = $minimum;
    }

    /**
     * @return $this
     */
    public function combineWith( Rule $other_rule )
    {
        if ($other_rule instanceof AboveRule) {
            if ($other_rule->getLimit() > $this->limit)
                $this->limit = $other_rule->getLimit();

        } elseif ($other_rule instanceof InRule) {
            // In rule cannot change the limit of in rule
        } else {
            throw new \Exception(
                'Rule combination with '.get_class($other_rule).' not implemented'
            );
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @return mixed
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * Checks if the range is valid.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return is_null($this->maximum)
            || is_null($this->minimum)
            || $this->maximum > $this->minimum;
    }

    /**/
}
