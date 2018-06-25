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
        if (!isset($this->operands[0])) {
            // TODO a NotRule with no operand should be simplified as
            //      a TrueRule
            throw new \LogicException(
                "Trying to get the field of a negation missing its operand"
            );
        }

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
    public function getValues()
    {
        return $this->getPossibilities();
    }

    /**
     */
    public function toArray($debug=false)
    {
        try {
            return [
                $this->getField(),
                $debug ? $this->getInstanceId() : self::operator,
                $this->getPossibilities(),
            ];
        }
        catch (\LogicException $e) {
            return parent::toArray();
        }
    }

    /**/
}
