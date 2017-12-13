<?php
namespace JClaveau\CustomFilter\Rule;

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
     * NotIn rule will always have a solution.
     *
     * @return bool
     * /
    public function hasSolution()
    {
        return true;
    }

    /**/
}
