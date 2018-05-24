<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a > x
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
        if (is_null($value)) {
            throw new \InvalidArgumentException(
                "Value parameter cannot be null, please use NullRule instead"
            );
        }

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
     */
    public function toArray()
    {
        return [
            $this->field,
            self::operator,
            $this->value,
        ];
    }

    /**/
}
