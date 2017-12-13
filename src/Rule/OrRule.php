<?php
namespace JClaveau\CustomFilter\Rule;

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

    /**/
}
