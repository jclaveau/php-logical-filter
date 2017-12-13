<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * Logical negation
 */
class NotRule extends AbstractOperationRule
{
    /**
     */
    public function __construct( $operand )
    {
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
     * @return array
     */
    // public function toAtomicRules()
    public function negateOperand()
    {
        $operand = $this->operands[0];

        if ($operand instanceof AboveRule) {
            $new_rule = new OrRule([
                new BelowRule($operand->getMinimum()),
                new EqualRule($operand->getMinimum()),
            ]);
        }
        elseif ($operand instanceof BelowRule) {
            // ! (v >  a) : v <= a : (v < a || a = v)
            $new_rule = new OrRule([
                new AboveRule($operand->getMaximum()),
                new EqualRule($operand->getMaximum()),
            ]);
        }
        elseif ($operand instanceof NotRule) {
            // ! (  !  a) : a
            $new_rule = $operand->getOperands()[0];
        }
        elseif ($operand instanceof EqualRule) {
            // ! (v =  a) : (v < a) || (v > a)
            $new_rule = new OrRule([
                new AboveRule($operand->getValue()),
                new BelowRule($operand->getValue()),
            ]);
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
                    $child_operands[0],
                    new NotRule($child_operands[1]),
                ]),
                new AndRule([
                    new NotRule($child_operands[0]),
                    $child_operands[1],
                ]),
                new AndRule([
                    new NotRule($child_operands[0]),
                    new NotRule($child_operands[1]),
                ]),
            ]);
        }
        elseif ($operand instanceof InRule) {
            // ! in [A, B, C] : !B && !A && !C
            $possibilities = $operand->getPossibilities();
            foreach ($possibilities as $i => $possibility)
                $possibilities[$i] = new NotRule($possibility);

            $new_rule = new AndRule($possibilities);
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
                new NotRule($child_operands[0]),
                new NotRule($child_operands[1]),
            ]);
        }
        elseif ($operand instanceof NotNullRule) {
            $new_rule = new NullRule();
        }
        elseif ($operand instanceof NullRule) {
            $new_rule = new NotNullRule();
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
