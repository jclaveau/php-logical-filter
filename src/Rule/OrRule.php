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
    /** @var string operator */
    const operator = 'or';

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
        $operandsAsArray = [self::operator];
        foreach ($this->operands as $operand)
            $operandsAsArray[] = $operand->toArray();

        return $operandsAsArray;
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
        if ($a->getMinimum() < $b->getMinimum())
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
        if ($a->getMaximum() > $b->getMaximum())
            return -1;

        return 1;
    }

    /**/
}
