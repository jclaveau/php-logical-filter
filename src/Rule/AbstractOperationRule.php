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
    public function __construct( array $operands=[] )
    {
        if ($nonRules = array_filter($operands, function($operand) {
            return !$operand instanceof AbstractRule;
        })) {
            throw new \InvalidArgumentException(
                "Operands must be instances of AbstractRule: \n"
                . var_export($nonRules, true)
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
     * Atomic Rules or the opposit of OperationRules: they are the leaves of
     * the RuleTree.
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

    /**
     * Simplify the current OperationRule.
     * + If an OrRule or an AndRule contains only one operand, it's equivalent
     *   to it.
     *
     * @todo Look for duplicates and remove them
     * @todo Look for rules having the same Operator and the same field to
     *       combine them.
     *
     * @return AbstractRule the simplified rule
     */
    public function simplify()
    {
        foreach ($this->operands as $i => &$operand) {
            if (method_exists($operand, 'simplify'))
                $operand = $operand->simplify();

            if (get_class($operand) == get_class($this)) {
                // Id AND is an operand on AND they can be merge (and the same with OR)
                foreach ($operand->getOperands() as $subOperand) {
                    $this->addOperand($subOperand);
                }
                unset($this->operands[$i]);
            }
        }

        // base the keys on 0 for easier tests comparison
        $this->operands = array_values($this->operands);

        if (count($this->operands) == 1) {
            return $this->operands[0];
        }

        return $this;
    }

    /**
     * Clones the rule and its operands.
     *
     * @return OperationRule A copy of the current instance with copied operands.
     */
    public function copy()
    {
        $copiedOperands = [];
        foreach ($this->operands as $operand) {
            $copiedOperands[] = $operand->copy();
        }

        $class = get_class($this);
        $copiedRule = new $class( $copiedOperands );

        return $copiedRule;
    }

    /**/
}
