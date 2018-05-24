<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule
{
    /**
     */
    public function getPossibilities()
    {
        return $this->possibilities;
    }

    /**
     * If a combination of rules have no possible solution (e.g. a > 10 && a < 5)
     * the all filter won't have any solution in any case. This is useful to
     * stop some process when the filter is not resolvable anymore.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
    }

    /**
     * During the simplification, it can become hard to understand where the
     * combine rule come from. The history is used to remember which rules led
     * to the current one.
     *
     * @param  Rule $rule The rule to store
     *
     * @return $this
     */
    public function storeRuleInHistory( Rule $rule )
    {
        $this->history[] = $rule;
        return $this;
    }

    /**
     * Checks if a rule is the same as another.
     *
     * @param  Rule $rule The rule that may be the same.
     *
     * @return true
     */
    public function equals( Rule $rule )
    {
        return $rule instanceof Rule;
    }

    /**
     * Checks if a rule has been combined into another by fetching recursivelly
     * its history, looking for the one in parameter.
     *
     * @param  Rule $rule The rule that may have been combined.
     *
     * @return true
     * /
    public function isCombinedWith( Rule $rule )
    {
        foreach ($this->history as $i => $combined_rule) {
            if ($combined_rule === $rule)
                return true;

            if ($combined_rule->isCombinedWith($rule))
                return true;
        }

        return false;
    }

    /**
     * Clones the rule with a chained syntax.
     *
     * @return Rule A copy of the current instance.
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Generates an orRule containing trees of rules without any orRule
     * inside.
     */
    public function gatherOrRulesAtRoot()
    {
    }

    /**
     * Push or rules as far as possible in the rule branches
     */
    public function splitOrRulesToLeafs()
    {
    }

    /**/
}
