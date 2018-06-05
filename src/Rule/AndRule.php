<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Logical conjunction:
 * @see https://en.wikipedia.org/wiki/Logical_conjunction
 */
class AndRule extends AbstractOperationRule
{
    /** @var string operator */
    const operator = 'and';

    /**
     * Replace all the OrRules of the RuleTree by one OrRule at its root.
     *
     * @todo rename as RootifyDisjunjctions?
     * @todo return $this (implements a Rule monad?)
     *
     * @return OrRule copied operands with one OR at its root
     */
    public function upLiftDisjunctions()
    {
        $this->moveSimplificationStepForward( self::disjunctions_rootified );

        $upLiftedOperands = [];
        foreach ($this->getOperands() as $operand) {
            $operand = $operand->copy();
            if ($operand instanceof AbstractOperationRule)
                $operand = $operand->upLiftDisjunctions();

            $upLiftedOperands[] = $operand;
        }

        // If the AndRule doesn't contain any OrRule , there is nothing to uplift
        if (!array_filter($upLiftedOperands, function($operand) {
            return $operand instanceof OrRule;
        })) {
            return new AndRule($upLiftedOperands);
        }

        $firstAndOperand = new AndRule();

        // This OrRule should contain only AndRules during its generation
        $upLiftedOr = new OrRule([
            $firstAndOperand
        ]);


        foreach ($upLiftedOperands as $i => $operand) {

            if ($operand instanceof NotRule) {
                throw new \LogicException(
                    'UpLifting disjunctions MUST be done after negations removal'
                );
            }
            elseif ($operand instanceof OrRule) {
                // If an operand is an Or, me transform the current
                // (A' || A") && (B')       <=> (A' && B') || (A" && B');
                // (A' || A") && (B' || B") <=> (A' && B') || (A' && B") || (A" && B') || (A" && B");
                // (A' || A") && (B' || B") && (C' || C") <=>
                //    (A' && B' && C') || (A' && B' && C") || (A' && B" && C') || (A' && B" && C")
                // || (A" && B' && C') || (A" && B' && C") || (A" && B" && C') || (A" && B" && C");
                $newUpLiftedOr = new OrRule;
                foreach ($operand->getOperands() as $subOperand) {
                    foreach ($upLiftedOr->getOperands() as $upLiftedOrSubOperand) {
                        $newUpLiftedOrSubOperand = $upLiftedOrSubOperand->copy();
                        $newUpLiftedOrSubOperand->addOperand( $subOperand->copy() );
                        $newUpLiftedOr->addOperand( $newUpLiftedOrSubOperand );
                    }
                }

                $upLiftedOr = $newUpLiftedOr;
            }
            else {
                // append the operand to all the operands of the $upLiftedOr
                foreach ($upLiftedOr->getOperands() as $upLifdtedOperand) {
                    if (!$upLifdtedOperand instanceof AndRule) {
                        throw new \LogicException(
                             "Operands of the uplifted OrRule MUST be AndRules during"
                            ."the combination."
                        );
                    }

                    $upLifdtedOperand->addOperand( $operand->copy() );
                }
            }
        }

        return $upLiftedOr;
    }

    /**
     */
    public function toArray($debug=false)
    {
        $operandsAsArray = [
            $debug ? $this->getInstanceId() : self::operator,
        ];
        foreach ($this->operands as $operand)
            $operandsAsArray[] = $operand->toArray($debug);

        return $operandsAsArray;
    }

    /**
     * Removes rule branches that cannot produce result like:
     * A = 1 || (B < 2 && B > 3) <=> A = 1
     *
     * @return null|AndRule The rule with removed invalid subrules or null
     *                      if it's invalid itself.
     */
    public function removeInvalidBranches()
    {
        $this->moveSimplificationStepForward(self::invalid_branches_removed);

        foreach ($this->operands as $i => $operand) {
            if ($operand instanceof AbstractOperationRule) {
                if (!$this->operands[$i] = $operand->removeInvalidBranches())
                    return null;
            }
        }

        $operandsByFields = $this->groupOperandsByFieldAndOperator();

        foreach ($operandsByFields as $field => $operandsByOperator) {

            if (!empty($operandsByOperator[ EqualRule::operator ])) {
                // There should never be multiple EqualRules after simplification
                if (count($operandsByOperator[ EqualRule::operator ]) != 1)
                    return null;

                $equalRule = reset($operandsByOperator[ EqualRule::operator ]);

                // There shouldn't be remaining AboveRules or BelowRules
                // after simplification, if there is already an EqualRule
                if (   !empty($operandsByOperator[ BelowRule::operator ])
                    || !empty($operandsByOperator[ AboveRule::operator ])) {
                    return null;
                }
            }
            elseif (   !empty($operandsByOperator[ BelowRule::operator ])
                    && !empty($operandsByOperator[ AboveRule::operator ])) {
                $aboveRule = reset($operandsByOperator[ AboveRule::operator ]);
                $belowRule = reset($operandsByOperator[ BelowRule::operator ]);

                if ($belowRule->getMaximum() <= $aboveRule->getMinimum())
                    return null;
            }
        }

        if (empty($this->operands))
            return null;

        return $this;
    }

    /**
     * Checks if a simplified AndRule has incompatible operands like:
     * + a = 3 && a > 4
     * + a = 3 && a < 2
     * + a > 3 && a < 2
     *
     * @return bool If the AndRule can have a solution or not
     *
     * @todo remove this weird recursion once the issue with upliftDisjunctions is ok
     */
    public function hasSolution()
    {
        $instance = $this->simplify()
            // ->dump(true)
            ;

        if ($instance instanceof AndRule) {
            $operandsByFields = $instance->groupOperandsByFieldAndOperator();
            foreach ($operandsByFields as $field => $operandsByOperator) {

                if (!empty($operandsByOperator[ EqualRule::operator ])) {
                    // There should never be multiple EqualRules after simplification
                    if (count($operandsByOperator[ EqualRule::operator ]) != 1)
                        return false;

                    $equalRule = reset($operandsByOperator[ EqualRule::operator ]);

                    // There shouldn't be remaining AboveRules or BelowRules
                    // after simplification, if there is already an EqualRule
                    if (   !empty($operandsByOperator[ BelowRule::operator ])
                        || !empty($operandsByOperator[ AboveRule::operator ])) {
                        return false;
                    }
                }
                elseif (   !empty($operandsByOperator[ BelowRule::operator ])
                        && !empty($operandsByOperator[ AboveRule::operator ])) {
                    $aboveRule = reset($operandsByOperator[ AboveRule::operator ]);
                    $belowRule = reset($operandsByOperator[ BelowRule::operator ]);

                    if ($belowRule->getMaximum() <= $aboveRule->getMinimum())
                        return false;
                }
            }

            foreach ($instance->getOperands() as $operand) {
                if (!$operand->hasSolution())
                    return false;
            }

            return true;
        }
        else {
            if (method_exists($instance, 'hasSolution'))
                return $instance->hasSolution();
        }

        return true;
    }


    /**
     * + if A = 2 && A > 1 <=> A = 2
     * + if A = 2 && A < 4 <=> A = 2
     */
    protected function simplifyDifferentOperands(array $operandsByFields)
    {
        foreach ($operandsByFields as $field => $operandsByOperator) {
            if (!empty($operandsByOperator[ EqualRule::operator ])) {
                if (count($operandsByOperator[ EqualRule::operator ]) != 1) {
                    // Multiple Equal rules for one field with different values has no sense
                    continue;
                }

                $equalRule = reset( $operandsByOperator[ EqualRule::operator ] );

                if (!empty($operandsByOperator[ AboveRule::operator ])) {
                    if (count($operandsByOperator[ AboveRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyOperands()"
                        );
                    }

                    $aboveRule = reset($operandsByOperator[ AboveRule::operator ]);
                    if ($aboveRule->getMinimum() < $equalRule->getValue())
                        unset($operandsByFields[ $field ][ AboveRule::operator ]);
                }

                if (!empty($operandsByOperator[ BelowRule::operator ])) {
                    if (count($operandsByOperator[ BelowRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyOperands()"
                        );
                    }

                    $belowRule = reset($operandsByOperator[ BelowRule::operator ]);
                    if ($belowRule->getMaximum() > $equalRule->getValue())
                        unset($operandsByFields[ $field ][ BelowRule::operator ]);
                }
            }
        }

        return $operandsByFields;
    }

    /**
     * This is called by the unifyOperands() method to choose which AboveRule
     * to keep for a given field.
     *
     * It's used as a usort() parameter.
     *
     * @return int -1|0|1
     */
    protected function aboveRuleUnifySorter( AboveRule $a, AboveRule $b)
    {
        if ($a->getMinimum() > $b->getMinimum())
            return -1;

        return 1;
    }

    /**
     * This is called by the unifyOperands() method to choose which BelowRule
     * to keep for a given field.
     *
     * It's used as a usort() parameter.
     *
     * @return int -1|0|1
     */
    protected function belowRuleUnifySorter( BelowRule $a, BelowRule $b)
    {
        if ($a->getMaximum() < $b->getMaximum())
            return -1;

        return 1;
    }

    /**/
}
