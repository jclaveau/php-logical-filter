<?php
namespace JClaveau\LogicalFilter\Rule;

abstract class AbstractRule implements \JsonSerializable
{
    use Trait_RuleWithOptions;
    use Trait_RuleWithCache;
    use Trait_ExportableRule;
    use Trait_RuleFactory;

    /**
     * Clones the rule with a chained syntax.
     *
     * @return AbstractRule A copy of the current instance.
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * @deprecated addMinimalCase
     */
    protected function forceLogicalCore()
    {
        return $this->addMinimalCase();
    }

    /**
     * Forces the two firsts levels of the tree to be an OrRule having
     * only AndRules as operands:
     * ['field', '=', '1'] <=> ['or', ['and', ['field', '=', '1']]]
     * As a simplified ruleTree will alwways be reduced to this structure
     * with no suboperands others than atomic ones or a simpler one like:
     * ['or', ['field', '=', '1'], ['field2', '>', '3']]
     *
     * This helpes to ease the result of simplify()
     *
     * @return OrRule
     */
    public function addMinimalCase()
    {
        // Simplification step is required to call hasSolution() on the
        // returned OrRule value
        if ($this instanceof AndRule || $this instanceof OrRule) {
            $simplification_step_to_keep = $this->getSimplificationStep();
        }
        elseif ($this->hasSolution()) {
            $simplification_step_to_keep = AbstractOperationRule::simplified;
        }
        else {
            $simplification_step_to_keep = null;
        }

        if (   $this instanceof AbstractAtomicRule
            || $this instanceof NotRule
            || $this instanceof InRule
            || ! $this->isNormalizationAllowed([])
        ) {
            $ruleTree = new OrRule([
                new AndRule([
                    $this,
                ]),
            ]);
        }
        elseif ($this instanceof AndRule) {
            $ruleTree = new OrRule([
                $this,
            ]);
        }
        elseif ($this instanceof OrRule) {
            foreach ($this->operands as $i => $operand) {
                if (! $operand instanceof AndRule) {
                    $this->operands[$i] = new AndRule([$operand]);
                }
            }
            $ruleTree = $this;
        }
        else {
            throw new \LogicException(
                "Unhandled type of simplified rules provided for conversion: "
                .$this
            );
        }

        if ($simplification_step_to_keep) {
            foreach ($operands = $ruleTree->getOperands() as $andOperand) {
                if (! $andOperand instanceof AndRule) {
                    throw new \LogicException(
                        "A rule is intended to be an and case: \n"
                        .$andOperand
                        ."\nof:\n"
                        .$ruleTree
                    );
                }

                $andOperand->moveSimplificationStepForward($simplification_step_to_keep, [], true);
            }
            $ruleTree->setOperands($operands);
            $ruleTree->moveSimplificationStepForward($simplification_step_to_keep, [], true);
        }


        return $ruleTree;
    }

    /**/
}
