<?php
namespace JClaveau\LogicalFilter\Rule;

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
     * + If an OrRule has an other OrRule as operand, they can be merged
     * + If an AndRule has an other AndRule as operand, they can be merged
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

        $this->unifyOperands();

        // base the keys on 0 for easier tests comparison
        $this->operands = array_values($this->operands);

        if (count($this->operands) == 1) {
            return $this->operands[0];
        }

        return $this;
    }

    /**
     * Indexes operands by their fields and operators. This sorting is
     * used during the simplification step.
     *
     * @return array The 3 dimensions array of operands: field > operator > i
     */
    protected function groupOperandsByFieldAndOperator()
    {
        $operandsByFields = [];
        foreach ($this->operands as $operand) {

            // Operation rules have no field but we need to keep them anyway
            $field = method_exists($operand, 'getField') ? $operand->getField() : '';

            if (!isset($operandsByFields[ $field ]))
                $operandsByFields[ $field ] = [];

            if (!isset($operandsByFields[ $field ][ $operand::operator ]))
                $operandsByFields[ $field ][ $operand::operator ] = [];

            $operandsByFields[ $field ][ $operand::operator ][] = $operand;
        }

        return $operandsByFields;
    }

    /**
     * Simplify the current AbstractOperationRule.
     *
     * @return AbstractOperationRule the simplified rule
     */
    public function unifyOperands($unifyDifferentOperands = true)
    {
        $operandsByFields = $this->groupOperandsByFieldAndOperator();

        // unifying same operands
        foreach ($operandsByFields as $field => $operandsByOperator) {
            foreach ($operandsByOperator as $operator => $operands) {
                if ($operator == AboveRule::operator) {
                    usort($operands, [$this, 'aboveRuleUnifySorter']);
                    $operands = [reset($operands)];
                }
                elseif ($operator == BelowRule::operator) {
                    usort($operands, [$this, 'belowRuleUnifySorter']);
                    $operands = [reset($operands)];
                }
                elseif ($operator == EqualRule::operator) {
                    $operandsTmp = array_map(function($operand) {
                        return serialize($operand);
                    }, $operands);

                    $operandsTmp = array_unique($operandsTmp);

                    $operands = array_map(function($operand) {
                        return unserialize($operand);
                    }, $operandsTmp);
                }

                $operandsByFields[ $field ][ $operator ] = $operands;
            }
        }

        if ($unifyDifferentOperands && $this instanceof AndRule) {
            // unifiying operands of different types
            $operandsByFields = $this->simplifyDifferentOperands($operandsByFields);
        }

        // Remove the index by fields and operators
        $unifiedOperands = [];
        foreach ($operandsByFields as $field => $operandsByOperator) {
            foreach ($operandsByOperator as $operator => $operands) {
                $unifiedOperands = array_merge($unifiedOperands, $operands);
            }
        }

        $this->operands = $unifiedOperands;

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
