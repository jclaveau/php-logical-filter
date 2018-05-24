<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Logical disjunction
 *
 * This class represents a rule that expect a value to be one of the list of
 * possibilities only.
 */
class OrRule extends AbstractOperationRule
{
    /**
     * @return $this
     */
    public function combineWith( OrRule $other_rule )
    {
        $this->operands = array_merge(
            $this->operands,
            $other_rule->getOperands()
        );

        return $this;
    }

    /**
     * NotIn rule will always have a solution.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
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

        return new OrRule($upLiftedOperands);
    }

    /**
     */
    public function toArray()
    {
        $operandsAsArray = ['or'];
        foreach ($this->operands as $operand)
            $operandsAsArray[] = $operand->toArray();

        return $operandsAsArray;
    }

    /**/
}
