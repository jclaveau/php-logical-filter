<?php
/**
 * BetweenOrEqualLowerRule
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

class BetweenOrEqualLowerRule extends BetweenRule
{
    /** @var string operator */
    const operator = '=><';

    /**
     */
    public function __construct( $field, array $limits )
    {
        if ($limits[0] == $limits[1]) {
            // (A = 1 || A > 1) && A < 1 <=> no sens
            // So if the two limits are equal we only consider the equality
            $this->addOperand( new EqualRule($field, $limits[0]) );
            $this->addOperand( new EqualRule($field, $limits[0]) );
        }
        else {
            $this->addOperand( new AboveOrEqualRule($field, $limits[0]) );
            $this->addOperand( new BelowRule($field, $limits[1]) );
        }
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        if ($this->operands[0] instanceof EqualRule)
            return $this->operands[0]->getValue();

        return $this->operands[0]->getMinimum();
    }

    /**
     * @return mixed
     */
    public function getMaximum()
    {
        if ($this->operands[1] instanceof EqualRule)
            return $this->operands[1]->getValue();

        return $this->operands[1]->getMaximum();
    }

    /**
     */
    public function getValues()
    {
        return [
            $this->getMinimum(),
            $this->getMaximum(),
        ];
    }

    /**/
}
