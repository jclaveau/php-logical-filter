<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Logical conjunction
 */
class AndRule extends AbstractOperationRule
{
    /**
     * Transforms all composite rules in the tree of operands into
     * atomic rules.
     *
     * @return array
     */
    public function toAtomicRules()
    {
        // Transforms the composite rules into OperationRules and AtomicRules
        $operands = [];
        foreach ($this->operands as $operand) {
            if ($operand instanceof AbstractAtomicRule)
                $operands[] = $operand;
            else
                $operands[] = $operand->toAtomicRules();
        }

        // remove disjunction : push all OR operations to the root of the chain

        $root_or = $this->conjuctify($operands);

        // merge the And rules and the atomic ones
        foreach ($root_or->getOperands() as $flat_operand) {
            $flat_operand->simplify();
        }

        // search conflicts
        // $flat_operand->simplify();

        return $this;
    }

    /**
     * Transforms a tree of operations including any number of ORs in
     * an OR rule (disjunction) having operands containing no other OR
     * operation.
     *
     * @retun OrRule
     */
    public function conjuctify($operands)
    {
        $conjuncted_operands = [];
        $final_cases_count   = 0;

        foreach ($operands as $operand) {
            $conjuncted_operand    =  $operand->conjunctify();
            $conjuncted_operands[] =  $conjuncted_operand;
            $final_cases_count     += $conjuncted_operand->countOperands();
        }

        $cases = new OrRule();
        for ($i = 0; $i < $final_cases_count; $i++) {
            $cases->addOperand( new AndRule() );
        }

        foreach ($conjuncted_operands as $conjuncted_operand) {
            foreach ($conjuncted_operand->getOperands() as $flat_operand) {
                for ($i = 0; $i < $final_cases_count; $i++) {
                    $cases[$i]->addOperand( $flat_operand->copy() );
                }
            }
        }

        return $cases;
    }

    /**
     * Replace all the OrRules of the RuleTree by one OrRule at its root.
     *
     * @todo renjame as RootifyDisjunjctions?
     * @return $this
     */
    public function upLiftDisjunctions()
    {
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

        // TODO : move the simplification later in the process?
        // return $upLiftedOr->simplify();
        return $upLiftedOr;
    }

    /**
     */
    public function toArray()
    {
        $operandsAsArray = ['and'];
        foreach ($this->operands as $operand)
            $operandsAsArray[] = $operand->toArray();

        return $operandsAsArray;
    }

    /**/
}
