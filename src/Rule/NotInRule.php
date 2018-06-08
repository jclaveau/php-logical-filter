<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a ! in x
 */
class NotInRule extends NotRule
{
    /** @var string operator */
    const operator = '!in';

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, array $possibilities )
    {
        $this->addOperand(new InRule($field, $possibilities));
    }

    /**
     */
    public function getField()
    {
        return $this->operands[0]->getField();
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        return $this->operands[0]->getPossibilities();
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $this->getField(),
            $debug ? $this->getInstanceId() : self::operator,
            $this->getPossibilities(),
        ];
    }

    /**/
}
