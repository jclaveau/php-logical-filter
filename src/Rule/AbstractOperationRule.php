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
     * @var array<AbstractRule> $parts
     */
    protected $operands;

    /**
     */
    public function __construct( array $operands )
    {
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
    public function removeNotRules()
    {
        foreach ($this->operands as $i => $operand) {
            if ($operand instanceof NotRule)
                $this->operands[$i] = $operand->negateOperand();
            elseif ($operand instanceof AbstractOperationRule)
                $operand->removeNotRules();
        }

        return $this;
    }

    /**/
}
