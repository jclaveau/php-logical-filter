<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Operation rules:
 * + Or
 * + And
 * + Not
 */
abstract class AbstractOperationRule extends AbstractRule
{
    /**
     * This property should never be null.
     *
     * @var array<AbstractRule> $operands
     */
    protected $operands = [];

    const negations_removed              = 'negations_removed';
    const operation_duplicates_removed   = 'operation_duplicates_removed';
    const disjunctions_rootified         = 'disjunctions_rootified';
    const atomic_operands_unified        = 'atomic_operands_unified';
    const monooperand_operations_removed = 'monooperand_operations_removed';
    const invalid_branches_removed       = 'invalid_branches_removed';
    const simplified                     = 'simplified';

    /**
     * The order is important!
     *
     * @var array $simplification_steps
     */
    const simplification_steps = [
        self::negations_removed,
        self::operation_duplicates_removed,
        self::disjunctions_rootified,
        self::atomic_operands_unified,
        self::monooperand_operations_removed,
        self::invalid_branches_removed,
        self::simplified,
    ];

    /**
     * @var null|string $simplified
     */
    protected $current_simplification_step = null;

    /**
     */
    public function __construct( array $operands=[] )
    {
        if ($nonRules = array_filter($operands, function($operand) {
            return !$operand instanceof AbstractRule;
        })) {
            throw new \InvalidArgumentException(
                "Operands must be instances of AbstractRule: \n"
                . var_export($nonRules, true)
            );
        }

        $this->operands = $operands;
    }

    /**
     * @return bool
     */
    public function isSimplified()
    {
        return $this->current_simplification_step == self::simplified;
    }

    /**
     * Adds an operand to the logical operation (&& or ||).
     *
     * @param  AbstractRule $new_operand
     *
     * @return $this
     */
    public function addOperand( AbstractRule $new_operand )
    {
        $this->operands[] = $new_operand;
        $this->current_simplification_step = null;
        return $this;
    }

    /**
     * @return array
     */
    public function getOperands()
    {
        return $this->operands;
    }

    /**
     * @return $this
     */
    public function setOperands(array $operands)
    {
        // keep the index start at 0
        $this->operands = array_values($operands);
        $this->current_simplification_step = null;
        return $this;
    }

    /**
     * @param string $step_to_go_to
     */
    public function moveSimplificationStepForward($step_to_go_to)
    {
        if (!in_array($step_to_go_to, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to go to: ".$step_to_go_to
            );
        }

        $steps_indices = array_flip(self::simplification_steps);

        if ($this->current_simplification_step != null) {
            $current_index = $steps_indices[ $this->current_simplification_step ];
            $target_index  = $steps_indices[ $step_to_go_to ];

            if ( $current_index < $target_index - 1 ) {
                throw new \LogicException(
                    "$step_to_go_to MUST be fullfilled after " . self::simplification_steps[$target_index - 1]
                    . " instead of the current step: " . $this->current_simplification_step
                    ."\nfor: " . $this
                );
            }

        }

        $this->current_simplification_step = $step_to_go_to;
    }

    /**
     * Checks if a simplification step is reached.
     *
     * @param  string step
     *
     * @return bool
     */
    public function simplicationStepReached($step)
    {
        if (!in_array($step, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step: ".$step
            );
        }

        if ($this->current_simplification_step == null)
            return false;

        $steps_indices = array_flip(self::simplification_steps);

        $current_index = $steps_indices[ $this->current_simplification_step ];
        $step_index    = $steps_indices[ $step ];

        return $current_index >= $step_index;
    }

    /**
     * Replace NotRule objects by the negation of their operands.
     *
     * @return $this
     */
    public function removeNegations()
    {
        $this->moveSimplificationStepForward(self::negations_removed);

        foreach ($this->operands as $i => $operand) {
            if ($operand instanceof NotRule) {
                $this->operands[$i] = $operand->negateOperand();
            }
            elseif ($operand instanceof AbstractOperationRule) {
                $operand->removeNegations();
                // try to remove negations twice as removing one can
                // produce some new ones
                $operand->removeNegations();
            }
        }

        return $this;
    }

    /**
     */
    public function removeUselessOperations()
    {
        $this->moveSimplificationStepForward( self::operation_duplicates_removed );

        foreach ($this->operands as $i => $operand) {
            if (get_class($operand) == get_class($this) && !$this instanceof NotRule) {
                // Id AND is an operand on AND they can be merge (and the same with OR)
                foreach ($operand->getOperands() as $subOperand) {
                    $this->addOperand( $subOperand->copy() );
                }
                unset($this->operands[$i]);
            }
        }

        return $this;
    }

    /**
     * Simplify the current AbstractOperationRule.
     *
     * @return AbstractOperationRule the simplified rule
     */
    public function unifyOperands($unifyDifferentOperands = true)
    {
        $this->moveSimplificationStepForward( self::atomic_operands_unified );

        $operandsByFields = $this->groupOperandsByFieldAndOperator();

        // unifying same operands
        foreach ($operandsByFields as $field => $operandsByOperator) {
            foreach ($operandsByOperator as $operator => $operands) {
                if ($operator == AboveRule::operator) {
                    usort($operands, [$this, 'aboveRuleUnifySorter']);
                    $operands = [reset($operands)];
                }
                elseif ($operator == BelowRule::operator) {
                    usort($operands, [$this, 'belowRuleUnifySorter']);
                    $operands = [reset($operands)];
                }
                elseif ($operator == EqualRule::operator) {
                    $operandsTmp = array_map(function($operand) {
                        return serialize($operand);
                    }, $operands);

                    $operandsTmp = array_unique($operandsTmp);

                    $operands = array_map(function($operand) {
                        return unserialize($operand);
                    }, $operandsTmp);
                }

                $operandsByFields[ $field ][ $operator ] = $operands;
            }
        }

        if ($unifyDifferentOperands && $this instanceof AndRule) {
            // unifiying operands of different types
            $operandsByFields = $this->simplifyDifferentOperands($operandsByFields);
        }

        // Remove the index by fields and operators
        $unifiedOperands = [];
        foreach ($operandsByFields as $field => $operandsByOperator) {
            foreach ($operandsByOperator as $operator => $operands) {
                $unifiedOperands = array_merge($unifiedOperands, $operands);
            }
        }

        $this->operands = $unifiedOperands;

        return $this;
    }

    /**
     * Simplify the current OperationRule.
     * + If an OrRule or an AndRule contains only one operand, it's equivalent
     *   to it.
     * + If an OrRule has an other OrRule as operand, they can be merged
     * + If an AndRule has an other AndRule as operand, they can be merged
     *
     * @return AbstractRule the simplified rule
     */
    public final function simplify($step_to_stop_after=null)
    {
        if ($step_to_stop_after && !in_array($step_to_stop_after, self::$simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to stop at: ".$step_to_stop_after
            );
        }

        if ($step_to_stop_after == self::simplified)
            return $this;

        $this->removeNegations();

        if ($step_to_stop_after == self::negations_removed)
            return $this;

        $this->removeUselessOperations();

        if ($step_to_stop_after == self::operation_duplicates_removed)
            return $this;

        // FIXME return $this while RootifyingDisjunctions!
        if (method_exists($this, 'upLiftDisjunctions')) {
            $instance = $this->upLiftDisjunctions();
        }
        else {
            $instance = $this;
        }

        if ($step_to_stop_after == self::disjunctions_rootified)
            return $instance;

        $instance->unifyOperands();

        if ($step_to_stop_after == self::atomic_operands_unified)
            return $instance;

        if (!$instance instanceof NotRule && count($instance->getOperands()) == 1) {
            return $instance->getOperands()[0];
        }

        $instance->moveSimplificationStepForward( self::monooperand_operations_removed );

        if ($step_to_stop_after == self::monooperand_operations_removed)
            return $instance;

        $instance->removeInvalidBranches();

        $instance->moveSimplificationStepForward( self::simplified );

        return $instance;
    }

    /**
     * Indexes operands by their fields and operators. This sorting is
     * used during the simplification step.
     *
     * @return array The 3 dimensions array of operands: field > operator > i
     */
    protected function groupOperandsByFieldAndOperator()
    {
        $operandsByFields = [];
        foreach ($this->operands as $operand) {

            // Operation rules have no field but we need to keep them anyway
            $field = method_exists($operand, 'getField') ? $operand->getField() : '';

            if (!isset($operandsByFields[ $field ]))
                $operandsByFields[ $field ] = [];

            if (!isset($operandsByFields[ $field ][ $operand::operator ]))
                $operandsByFields[ $field ][ $operand::operator ] = [];

            $operandsByFields[ $field ][ $operand::operator ][] = $operand;
        }

        return $operandsByFields;
    }

    /**
     * Clones the rule and its operands.
     *
     * @return OperationRule A copy of the current instance with copied operands.
     */
    public function copy()
    {
        $copiedOperands = [];
        foreach ($this->operands as $operand) {
            $copiedOperands[] = $operand->copy();
        }

        $class = get_class($this);
        $copiedRule = new $class( $copiedOperands );

        return $copiedRule;
    }

    /**/
}
