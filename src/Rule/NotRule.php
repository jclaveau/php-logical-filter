<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Logical negation
 */
class NotRule extends AbstractOperationRule
{
    /**
     */
    public function __construct( AbstractRule $operand=null )
    {
        if (!$operand)
            return;

        if (!$operand instanceof AbstractRule) {
            throw new \InvalidArgumentException(
                "Operand of NOT must be an instance of AbstractRule"
            );
        }

        // negation has only one operand
        $this->operands = [$operand];
    }

    /**
     * Transforms all composite rules in the tree of operands into
     * atomic rules.
     *
     * @todo use get_class instead of instanceof to avoid order issue
     *       in the conditions.
     *
     * @return array
     */
    // public function toAtomicRules()
    public function negateOperand()
    {
        $operand = $this->operands[0];

        if (!$operand instanceof AbstractOperationRule)
            $field = $operand->getField();

        if ($operand instanceof AboveRule) {
            $new_rule = new OrRule([
                new BelowRule($field, $operand->getMinimum()),
                new EqualRule($field, $operand->getMinimum()),
            ]);
        }
        elseif ($operand instanceof BelowRule) {
            // ! (v >  a) : v <= a : (v < a || a = v)
            $new_rule = new OrRule([
                new AboveRule($field, $operand->getMaximum()),
                new EqualRule($field, $operand->getMaximum()),
            ]);
        }
        elseif ($operand instanceof NotRule) {
            // ! (  !  a) : a
            $new_rule = $operand->getOperands()[0];
        }
        elseif ($operand instanceof EqualRule) {
            // ! (v =  a) : (v < a) || (v > a)
            $new_rule = new OrRule([
                new AboveRule($field, $operand->getValue()),
                new BelowRule($field, $operand->getValue()),
            ]);
        }
        elseif ($operand instanceof InRule) {
            // ! in [A, B, C] : !B && !A && !C
            // In rule must remain before OrRule as it extends it
            $possibilities = $operand->getPossibilities();

            foreach ($possibilities as $i => $possibility)
                $possibilities[$i] = new NotRule($possibility);

            $new_rule = new AndRule($possibilities);
        }
        elseif ($operand instanceof AndRule) {
            // ! (B && A) : (!B && A) || (B && !A) || (!B && !A)
            // TODO : n operands ?
            $child_operands = $operand->getOperands();

            if (count($child_operands) > 2) {
                throw new \ErrorException(
                     'NotRule resolution of AndRule with more than 3 '
                    .'operands is not implemented'
                );
            }

            $new_rule = new OrRule([
                new AndRule([
                    $child_operands[0]->copy(),
                    new NotRule($child_operands[1]->copy()),
                ]),
                new AndRule([
                    new NotRule($child_operands[0]->copy()),
                    $child_operands[1]->copy(),
                ]),
                new AndRule([
                    new NotRule($child_operands[0]->copy()),
                    new NotRule($child_operands[1]->copy()),
                ]),
            ]);
        }
        elseif ($operand instanceof OrRule) {
            // ! (B || A) : !B && !A
            $child_operands = $operand->getOperands();

            if (count($child_operands) > 2) {
                throw new \ErrorException(
                     'NotRule resolution of OrRule with more than 3 '
                    .'operands is not implemented'
                );
            }

            $new_rule = new AndRule([
                new NotRule($child_operands[0]->copy()),
                new NotRule($child_operands[1]->copy()),
            ]);
        }
        elseif ($operand instanceof NotNullRule) {
            $new_rule = new NullRule($field);
        }
        elseif ($operand instanceof NullRule) {
            $new_rule = new NotNullRule($field);
        }
        else {
            throw new \ErrorException(
                'Removing NotRule of ' . get_class($operand)
                . ' not implemented'
            );
        }

        return $new_rule;
    }

    /**/
}
