<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a != x
 */
class NotEqualRule extends NotRule
{
    /** @var string operator */
    const operator = '!=';

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

        $this->addOperand(new EqualRule($field, $value));
    }

    /**
     */
    public function getField()
    {
        return $this->operands[0]->getField();
    }

    /**
     */
    public function getValue()
    {
        return $this->operands[0]->getValue();
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $this->getField(),
            $debug ? $this->getInstanceId() : self::operator,
            $this->getValue(),
        ];
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
