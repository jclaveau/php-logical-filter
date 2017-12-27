<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * Operation rules:
 * + Or
 * + And
 * + Not
 */
abstract class AbstractOperationRule extends AbstractRule
{
    /**
     * This property should never be null.
     *
     * @var array<AbstractRule> $operands
     */
    protected $operands = [];

    /**
     */
    public function __construct( array $operands=null )
    {
        if (!$operands)
            return;

        if (!array_filter($operands, function($operand) {
            return $operand instanceof AbstractRule;
        })) {
            throw new \InvalidArgumentException(
                "Operands must be instances of AbstractRule"
            );
        }

        $this->operands = $operands;
    }

    /**
     * Adds an operand to the logical operation (&& or ||).
     *
     * @param  AbstractRule $new_operand
     *
     * @return $this
     */
    public function addOperand( AbstractRule $new_operand )
    {
        $this->operands[] = $new_operand;
        return $this;
    }

    /**
     * @return $this
     */
    public function combineWith( AbstractOperationRule $other_rule )
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getOperands()
    {
        return $this->operands;
    }

    /**
     * Checks if the rule do not expect the value to be above infinity.
     *
     * @todo negative infinite?
     *
     * @return bool
     */
    public function isAtomic()
    {
        return false;
    }

    /**
     * Replace NotRule objects by the negation of their operands.
     *
     * @return $this
     */
    public function removeNegations()
    {
        foreach ($this->operands as $i => $operand) {
            if ($operand instanceof NotRule) {
                $this->operands[$i] = $operand->negateOperand();
            }
            elseif ($operand instanceof AbstractOperationRule) {
                $operand->removeNegations();
                // try to remove negations twice as removing one can
                // produce some new ones
                $operand->removeNegations();
            }
        }

        return $this;
    }

    /**/
}
