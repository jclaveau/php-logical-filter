<?php
namespace JClaveau\LogicalFilter\Rule;
use       JClaveau\VisibilityViolator\VisibilityViolator;

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
    public function rootifyDisjunctions()
    {
        if (!$this->isSimplificationAllowed())
            return $this;

        $this->moveSimplificationStepForward( self::rootify_disjunctions );

        $upLiftedOperands = [];
        foreach ($this->getOperands() as $operand) {
            $operand = $operand->copy();
            if ($operand instanceof AbstractOperationRule)
                $operand = $operand->rootifyDisjunctions();

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

        // var_dump($upLiftedOperands);
        // $this->dump(true);

        foreach ($upLiftedOperands as $i => $operand) {

            if ($operand instanceof NotRule) {
                if ($operand instanceof NotEqualRule && $operand->getValue() === null) {
                    foreach ($upLiftedOr->getOperands() as $upLifdtedOperand) {
                        $upLifdtedOperand->addOperand( $operand->copy() );
                    }
                }
                else {
                    throw new \LogicException(
                        'UpLifting disjunctions MUST be done after negations removal'
                    );
                }
            }
            elseif ($operand instanceof OrRule && $operand->isSimplificationAllowed()) {

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
                        if ($newUpLiftedOrSubOperand->simplify()->hasSolution())
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
     * @return AndRule $this
     */
    public function removeInvalidBranches()
    {
        if (!$this->isSimplificationAllowed())
            return $this;

        $this->moveSimplificationStepForward(self::remove_invalid_branches);

        foreach ($this->operands as $i => $operand) {
            if ($operand instanceof AndRule || $operand instanceof OrRule ) {
                $this->operands[$i] = $operand->removeInvalidBranches();
                if (!$this->operands[$i]->hasSolution()) {
                    $this->operands = [];
                    return $this;
                }
            }
        }

        $operandsByFields = $this->groupOperandsByFieldAndOperator();

        // $this->dump(true);

        foreach ($operandsByFields as $field => $operandsByOperator) {

            if (!empty($operandsByOperator[ EqualRule::operator ])) {

                foreach ($operandsByOperator[ EqualRule::operator ] as $equalRule) {
                    // Multiple equal rules without the same value is invalid
                    if (isset($previousEqualRule) && $previousEqualRule->getValue() != $equalRule->getValue()) {
                        $this->operands = [];
                        return $this;
                    }
                    $previousEqualRule = $equalRule;
                }
                unset($previousEqualRule);

                $equalRule = reset($operandsByOperator[ EqualRule::operator ]);

                if (   !empty($operandsByOperator[ BelowRule::operator ])
                    && $equalRule->getValue() === null
                ) {
                    $this->operands = [];
                    return $this;
                }

                if (   !empty($operandsByOperator[ BelowRule::operator ])
                    && $equalRule->getValue() >= reset($operandsByOperator[ BelowRule::operator ])->getMaximum()
                ) {
                    $this->operands = [];
                    return $this;
                }

                if (   !empty($operandsByOperator[ AboveRule::operator ])
                    && $equalRule->getValue() === null
                ) {
                    $this->operands = [];
                    return $this;
                }

                if (   !empty($operandsByOperator[ AboveRule::operator ])
                    && $equalRule->getValue() <= reset($operandsByOperator[ AboveRule::operator ])->getMinimum()
                ) {
                    $this->operands = [];
                    return $this;
                }

                if (   !empty($operandsByOperator[ NotEqualRule::operator ])
                    && $equalRule->getValue() === null
                    && reset($operandsByOperator[ NotEqualRule::operator ])->getValue() === null
                ) {
                    $this->operands = [];
                    return $this;
                }
            }
            elseif (   !empty($operandsByOperator[ BelowRule::operator ])
                    && !empty($operandsByOperator[ AboveRule::operator ])) {
                $aboveRule = reset($operandsByOperator[ AboveRule::operator ]);
                $belowRule = reset($operandsByOperator[ BelowRule::operator ]);

                if ($belowRule->getMaximum() <= $aboveRule->getMinimum()) {
                    $this->operands = [];
                    return $this;
                }
            }
        }

        return $this;
    }

    /**
     * Checks if a simplified AndRule has incompatible operands like:
     * + a = 3 && a > 4
     * + a = 3 && a < 2
     * + a > 3 && a < 2
     *
     * @return bool If the AndRule can have a solution or not
     */
    public function hasSolution()
    {
        if (!$this->simplicationStepReached(self::simplified)) {
            throw new \LogicException(
                "hasSolution has no sens if the rule is not simplified instead of being at: "
                .var_export($this->current_simplification_step, true)
            );
        }

        // atomic rules
        foreach ($this->getOperands() as $operand) {
            if (method_exists($operand, 'hasSolution') && !$operand->hasSolution())
                return false;
        }

        return !empty($this->getOperands());
    }


    /**
     * + if A > 2 && A > 1 <=> A > 2
     * + if A < 2 && A < 1 <=> A < 1
     */
    protected function simplifySameOperands(array $operandsByFields)
    {
        // unifying same operands
        foreach ($operandsByFields as $field => $operandsByOperator) {

            foreach ($operandsByOperator as $operator => $operands) {
                unset($previous_operand);

                try {
                    if ($operator == AboveRule::operator) {
                        usort($operands, function( AboveRule $a, AboveRule $b ) {
                            if ($a->getMinimum() === null)
                                return 1;

                            if ($b->getMinimum() === null)
                                return -1;

                            if ($a->getMinimum() > $b->getMinimum())
                                return -1;

                            return 1;
                        });
                        $operands = [reset($operands)];
                    }
                    elseif ($operator == BelowRule::operator) {
                        usort($operands, function( BelowRule $a, BelowRule $b ) {
                            if ($a->getMaximum() === null)
                                return 1;

                            if ($b->getMaximum() === null)
                                return -1;

                            if ($a->getMaximum() < $b->getMaximum())
                                return -1;

                            return 1;
                        });
                        $operands = [reset($operands)];
                    }
                    elseif ($operator == EqualRule::operator) {
                        // TODO add an option for the support strict comparison
                        foreach ($operands as $i => $operand) {
                            if (!isset($previous_operand)) {
                                $previous_operand = $operand;
                                continue;
                            }

                            if ($previous_operand == $operand)
                                unset($operands[$i]);
                        }
                    }
                    elseif ($operator == InRule::operator) {
                        foreach ($operands as $i => $operand) {
                            if (!isset($previous_operand)) {
                                $previous_operand = $operand;
                                continue;
                            }

                            $previous_operand->setPossibilities( array_intersect(
                                $previous_operand->getPossibilities(),
                                $operand->getPossibilities()
                            ) );
                        }
                    }
                    elseif ($operator == NotInRule::operator) {
                        foreach ($operands as $i => $operand) {
                            if (!isset($previous_operand)) {
                                $previous_operand = $operand;
                                continue;
                            }

                            $previous_operand->setPossibilities( array_merge(
                                $previous_operand->getPossibilities(),
                                $operand->getPossibilities()
                            ) );

                            unset($operands[$i]);
                        }
                    }
                }
                catch (\Exception $e) {
                    VisibilityViolator::setHiddenProperty($e, 'message', $e->getMessage() . "\n" . var_export([
                            'operand' => $operand,
                            'this'    => $this,
                        ], true)
                    );

                    // \Debug::dumpJson($this->toArray(), true);
                    throw $e;
                }

                $operandsByFields[ $field ][ $operator ] = $operands;
            }
        }

        return $operandsByFields;
    }

    /**
     * + if A = 2 && A > 1 <=> A = 2
     * + if A = 2 && A < 4 <=> A = 2
     */
    protected function simplifyDifferentOperands(array $operandsByFields)
    {
        foreach ($operandsByFields as $field => $operandsByOperator) {

            // EqualRule comparisons
            if (!empty($operandsByOperator[ EqualRule::operator ])) {
                if (count($operandsByOperator[ EqualRule::operator ]) != 1) {
                    // Multiple Equal rules for one field with different values has no sense
                    continue;
                }

                $equalRule = reset( $operandsByOperator[ EqualRule::operator ] );

                if (!empty($operandsByOperator[ AboveRule::operator ])) {
                    if (count($operandsByOperator[ AboveRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $aboveRule = reset($operandsByOperator[ AboveRule::operator ]);
                    if ($equalRule->getValue() !== null && $aboveRule->getMinimum() < $equalRule->getValue())
                        unset($operandsByFields[ $field ][ AboveRule::operator ]);
                }

                if (!empty($operandsByOperator[ BelowRule::operator ])) {
                    if (count($operandsByOperator[ BelowRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $belowRule = reset($operandsByOperator[ BelowRule::operator ]);
                    if ($equalRule->getValue() !== null && $belowRule->getMaximum() > $equalRule->getValue())
                        unset($operandsByFields[ $field ][ BelowRule::operator ]);
                }

                if (!empty($operandsByOperator[ InRule::operator ])) {
                    if (count($operandsByOperator[ InRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $possibilities = reset($operandsByOperator[ InRule::operator ])->getPossibilities();

                    if (in_array($equalRule->getValue(), $possibilities)) {
                        unset($operandsByFields[ $field ][ EqualRule::operator ]);
                    }
                    else {
                        // We flush possibilities of the InRule
                        // TODO Replace it by a FalseRule
                        $operandsByFields[ $field ][ InRule::operator ]->setPossibilities([]);
                        // and also remove the equal rule to shorten the reste of the simplification process
                        unset($operandsByFields[ $field ][ EqualRule::operator ]);
                    }
                }

                if (!empty($operandsByOperator[ NotInRule::operator ])) {
                    if (count($operandsByOperator[ NotInRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $notInRule = reset($operandsByOperator[ NotInRule::operator ]);

                    $operandsByFields[ $field ][ NotInRule::operator ]->setPossibilities(
                        array_diff($notInRule->getPossibilities(), [$equalRule->getValue()])
                    );
                }
            }

            // NotEqualRule null comparisons
            if (!empty($operandsByOperator[ NotEqualRule::operator ])) {
                $notEqualRule = reset( $operandsByOperator[ NotEqualRule::operator ] );

                if ($notEqualRule->getValue() === null) {
                    if (!empty($operandsByOperator[ AboveRule::operator ])) {
                        if (count($operandsByOperator[ AboveRule::operator ]) != 1) {
                            throw new \LogicException(
                                __METHOD__ . " MUST be called after unifyAtomicOperands()"
                            );
                        }

                        unset($operandsByFields[ $field ][ NotEqualRule::operator ]);
                    }

                    if (!empty($operandsByOperator[ BelowRule::operator ])) {
                        if (count($operandsByOperator[ BelowRule::operator ]) != 1) {
                            throw new \LogicException(
                                // TODO we are currently in unifyAtomicOperands() :/
                                __METHOD__ . " MUST be called after unifyAtomicOperands()"
                            );
                        }

                        unset($operandsByFields[ $field ][ NotEqualRule::operator ]);
                    }

                    if (!empty($operandsByOperator[ EqualRule::operator ])) {
                        if (count($operandsByOperator[ EqualRule::operator ]) != 1) {
                            throw new \LogicException(
                                // TODO we are currently in unifyAtomicOperands() :/
                                __METHOD__ . " MUST be called after unifyAtomicOperands()"
                            );
                        }

                        if (reset($operandsByOperator[ EqualRule::operator ])->getValue() !== null)
                            unset($operandsByFields[ $field ][ NotEqualRule::operator ]);
                    }
                }

                if (!empty($operandsByOperator[ NotInRule::operator ])) {
                    if (count($operandsByOperator[ NotInRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $notInRule = reset($operandsByOperator[ NotInRule::operator ]);
                    if (in_array($notEqualRule->getValue(), $notInRule->getPossibilities())) {
                        unset($operandsByFields[ $field ][ NotInRule::operator ]);
                    }
                    else {
                        // We flush possibilities of the NotInRule
                        // TODO Replace it by a FalseRule
                        $operandsByFields[ $field ][ NotInRule::operator ]->setPossibilities([]);
                    }
                }

                if (!empty($operandsByOperator[ InRule::operator ])) {
                    if (count($operandsByOperator[ InRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $inRule = reset($operandsByOperator[ InRule::operator ]);

                    $operandsByFields[ $field ][ InRule::operator ][0]->setPossibilities(
                        array_diff($inRule->getPossibilities(), [$notEqualRule->getValue()])
                    );
                }
            }

            // Comparison between InRules and NotInRules
            // This is an optimization to avoid NotIn explosion
            if (!empty($operandsByOperator[ InRule::operator ])) {
                $inRule = $operandsByOperator[ InRule::operator ][0];

                if (!empty($operandsByOperator[ NotInRule::operator ])) {
                    if (count($operandsByOperator[ NotInRule::operator ]) != 1) {
                        throw new \LogicException(
                            __METHOD__ . " MUST be called after unifyAtomicOperands()"
                        );
                    }

                    $notInRule = reset($operandsByOperator[ NotInRule::operator ]);
                    $operandsByFields[ $field ][ NotInRule::operator ][0]->setPossibilities(
                        array_diff( $inRule->getPossibilities(), $notInRule->getPossibilities())
                    );
                    unset($operandsByFields[ $field ][ NotInRule::operator ]);
                }
            }
        }

        return $operandsByFields;
    }

    /**/
}
