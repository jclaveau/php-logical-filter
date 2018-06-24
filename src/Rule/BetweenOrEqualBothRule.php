<?php
/**
 * BetweenOrEqualBoth
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

class BetweenOrEqualBothRule extends BetweenRule
{
    /** @var string operator */
    const operator = '=><=';

    /**
     */
    public function __construct( $field, array $limits )
    {
        $this->addOperand( new AboveOrEqualRule($field, $limits[0]) );
        $this->addOperand( new BelowOrEqualRule($field, $limits[1]) );
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
