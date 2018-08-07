<?php
namespace JClaveau\LogicalFilter\Rule;
use       JClaveau\VisibilityViolator\VisibilityViolator;

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

    const remove_negations        = 'remove_negations';
    const rootify_disjunctions    = 'rootify_disjunctions';
    const unify_atomic_operands   = 'unify_atomic_operands';
    const remove_invalid_branches = 'remove_invalid_branches';    // simplified after this step

    const simplified              = self::remove_invalid_branches;

    /**
     * The order is important!
     *
     * @var array $simplification_steps
     */
    const simplification_steps = [
        AbstractOperationRule::remove_negations,
        AbstractOperationRule::rootify_disjunctions,
        AbstractOperationRule::unify_atomic_operands,
        AbstractOperationRule::remove_invalid_branches,
    ];

    /**
     * @var null|string $simplified
     */
    protected $current_simplification_step = null;

    /**
     */
    public function __construct( array $operands=[] )
    {
        $this->setOperands( $operands );
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
        if (!isset($this->operands[ $id = $new_operand->getSemanticId() ]))
            $this->operands[ $id ] = $new_operand;

        if ($this->current_simplification_step)
            $this->current_simplification_step = null;

        return $this;
    }

    /**
     * @return array
     */
    public function getOperands()
    {
        return array_values( $this->operands );
    }

    /**
     * @return $this
     */
    public function setOperands(array $operands)
    {
        $this->operands = [];
        foreach ($operands as $operand) {
            $this->addOperand($operand);
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return string $this
     */
    public function renameFields($renamings)
    {
        foreach ($this->operands as $operand) {
            if (method_exists($operand, 'renameField'))
                $operand->renameField($renamings);
            else
                $operand->renameFields($renamings);
        }

        return $this;
    }

    /**
     * @param string $step_to_go_to
     */
    public function moveSimplificationStepForward($step_to_go_to, $force=false)
    {
        if (!in_array($step_to_go_to, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to go to: ".$step_to_go_to
            );
        }

        if ($this->isSimplificationAllowed() && !$force && $this->current_simplification_step != null) {
            $steps_indices = array_flip(self::simplification_steps);

            $current_index = $steps_indices[ $this->current_simplification_step ];
            $target_index  = $steps_indices[ $step_to_go_to ];

            if ( $current_index >= $target_index ) {
                // allow recall of previous step without going back
                return;
            }
            elseif ( $current_index < $target_index - 1 ) {
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
        if (!$this->isSimplificationAllowed())
            return $this;

        $this->moveSimplificationStepForward(self::remove_negations);

        foreach ($this->operands as $i => $operand) {

            if ($operand instanceof NotRule) {
                $this->operands[$i] = $operand->negateOperand();
            }

            if ($this->operands[$i] instanceof AbstractOperationRule) {
                $this->operands[$i]->removeNegations();
            }
        }

        return $this;
    }

    /**
     * Operation cleaning consists of removing operation with one operand
     * and removing operations having a same type of operation as operand.
     *
     * This operation has been required between every steps until now.
     *
     * @toopt Trigger cleaning requirement during simplification steps
     *
     * @return $this;
     */
    public function cleanOperations($recurse=true)
    {
        if ($recurse) foreach ($this->operands as $i => $operand) {
            if ($operand instanceof AbstractOperationRule && !$operand instanceof InRule && !$operand instanceof NotRule) {
                $this->operands[$i] = $operand->cleanOperations();
            }
        }

        $is_modified = true;
        while ($is_modified) {
            $is_modified = false;

            if ($this->removeMonooperandOperationsOperands())
                $is_modified = true;

            if ($this->removeSameOperationOperands())
                $is_modified = true;
        }

        return $this;
    }


    /**
     * If a child is an OrRule or an AndRule and has only one child,
     * replace it by its child.
     *
     * @used-by removeSameOperationOperands() Ping-pong recursion
     *
     * @return bool If something has been simplified or not
     */
    public function removeMonooperandOperationsOperands()
    {
        foreach ($this->operands as $i => $operand) {
            if (!$operand instanceof AbstractOperationRule)
                continue;

            if ($operand instanceof InRule && !$operand->isSimplificationAllowed()) {
                $count = count($operand->getPossibilities());
            }
            else {
                $count = count($operand->getOperands());
            }

            if (
                    ($operand instanceof AndRule || $operand instanceof OrRule)
                &&  $count == 1
            ) {
                $sub_operands = $operand->getOperands();
                $this->operands[$i] = reset($sub_operands);
                $has_been_changed = true;
            }
        }

        return !empty($has_been_changed);
    }

    /**
     * Removes duplicates between the current AbstractOperationRule.
     *
     * @return AbstractOperationRule the simplified rule
     */
    public function unifyAtomicOperands($simplification_strategy_step = false)
    {
        // if (!$this->isSimplificationAllowed())
            // return $this;

        if ($simplification_strategy_step)
            $this->moveSimplificationStepForward( self::unify_atomic_operands );

        // $this->dump(true);

        if (!$this->isSimplificationAllowed())
            return $this;

        foreach ($this->operands as $operand) {
            if ($operand instanceof AbstractOperationRule) {
                $operand->unifyAtomicOperands($simplification_strategy_step);
            }
        }

        $operandsByFields = $this->groupOperandsByFieldAndOperator();
        // var_dump($operandsByFields);

        $operandsByFields = $this->simplifySameOperands($operandsByFields);
        // var_dump($operandsByFields);

        if ($this instanceof AndRule) {
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
     * @param  array $options stop_after | stop_before | force_logical_core
     *
     * @return AbstractRule the simplified rule
     */
    public final function simplify($options=[])
    {
        $step_to_stop_before = !empty($options['stop_before'])        ? $options['stop_before'] : null;
        $step_to_stop_after  = !empty($options['stop_after'])         ? $options['stop_after']  : null;
        $force_logical_core  = !empty($options['force_logical_core']) ? $options['force_logical_core'] : false;

        if ($step_to_stop_before && !in_array($step_to_stop_before, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to stop at: ".$step_to_stop_before
            );
        }

        $this->cleanOperations();
        $this->unifyAtomicOperands();
        // $this->dump(true);

        if ($step_to_stop_before == self::remove_negations)
            return $this;

        $this->removeNegations();

        // $this->dump(true);

        if ($step_to_stop_after  == self::remove_negations ||
            $step_to_stop_before == self::rootify_disjunctions )
            return $this;

        // $this->dump(true);

        $this->cleanOperations();
        // FIXME return $this while RootifyingDisjunctions!
        if (method_exists($this, 'rootifyDisjunctions')) {
            $instance = $this->rootifyDisjunctions();
        }
        else {
            $instance = $this;
        }

        // $instance->dump(true);

        if ($step_to_stop_after  == self::rootify_disjunctions ||
            $step_to_stop_before == self::unify_atomic_operands )
            return $instance;

        if (!$instance instanceof AbstractAtomicRule) {

            $instance->cleanOperations();
            $instance->unifyAtomicOperands(true);

            // $instance->dump(true);

            if ($step_to_stop_after  == self::unify_atomic_operands ||
                $step_to_stop_before == self::remove_invalid_branches )
                return $instance;

            $instance->cleanOperations();
            if (method_exists($instance, 'removeInvalidBranches')) {
                $instance->removeInvalidBranches();
            }
        }

        // $instance->dump(true);
        $instance->cleanOperations();

        // the root rule cannot be cleaned so we wrap it and apply a
        // last non recursive clean
        // TODO kind of monad|become|cese
        //@see https://github.com/jclaveau/php-logical-filter/issues/20
        if ($instance instanceof AndRule || $instance instanceof OrRule ) {

            if (!$instance->getOperands())
                return $instance;

            $operands = (new AndRule([$instance]))
                ->cleanOperations(false)
                // ->dump(true)
                ->getOperands();

            if (count($operands) == 1)
                $instance = reset($operands);
        }

        if ($force_logical_core) {
            $instance = $instance->forceLogicalCore();
            // for the simplification status at
            foreach ($operands = $instance->getOperands() as &$andOperand) {
                $andOperand->moveSimplificationStepForward(self::simplified, true);
            }
            $instance->setOperands($operands);
            $instance->moveSimplificationStepForward(self::simplified, true);
        }

        return $instance;
    }

    /**
     * Indexes operands by their fields and operators. This sorting is
     * used during the simplification step.
     *
     * @return array The 3 dimensions array of operands: field > operator > i
     */
    public function groupOperandsByFieldAndOperator()
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
    public final function copy($token=null)
    {
        // copying the instance first avoids the copy_cache array to be copied
        $copied_rule = clone $this;

        // TODO enable copy registration only in debug mode
        // if ($copy = $this->registerCopy($token))
            // return $copy;

        $copied_operands = [];
        foreach ($this->operands as $operand_id => &$operand) {
            $copied_operands[$operand_id] = $operand->copy($token);
        }

        VisibilityViolator::setHiddenProperty(
            $copied_rule,
            'operands',
            $copied_operands
        );

        $this->copy_cache = [];
        // Emptying the gc once the explicitly required copy() has ended
        // too slow
        // if (!array_filter(func_get_args()))
            // gc_collect_cycles();

        return $copied_rule;
    }


    /** @var array $copy_cache */
    private $copy_cache = [];

    /**
     */
    protected final function registerCopy( &$token )
    {
        if ($token === null)
            $token = $this->getInstanceId().'-'.uniqid();

        if (!isset($this->copy_cache[ $token ]))
            $this->copy_cache[$token] = [];

        if (isset($this->copy_cache[$token][ $this->getInstanceId() ])) {
            throw new \Exception(
                 "Copying multiple times the same object during the copy recursion "
                ."identified by $token: {$this->getInstanceId()} => " . $this
            );
            // return $this->copy_cache[$token][ $this->getInstanceId() ];
        }

        $this->copy_cache[$token][ $this->getInstanceId() ] = $this;
    }

    /**
     */
    public function isSimplificationAllowed()
    {
        return true;
    }

    /**
     * Returns an operand based on its position
     *
     * @return AbstractRule|null The operand if it exists or null
     */
    protected function getOperandAt($index=0)
    {
        $operands = array_values($this->operands);
        if (isset($operands[$index]))
            return $operands[$index];
    }

    /**/
}
