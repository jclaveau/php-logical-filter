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
        if (!is_scalar($minimum)) {
            throw new \InvalidArgumentException(
                "Minimum parameter must be a scalar"
            );
        }

        $this->field   = $field;
        $this->minimum = $minimum;
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
        return !(is_infinite( $this->minimum ) && $this->minimum > 0)
            && (!is_numeric( $this->minimum ) || !is_nan( $this->minimum ));
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $this->field,
            $debug ? get_class($this).':'.spl_object_id($this) : self::operator,
            $this->minimum,
        ];
    }

    /**/
}
