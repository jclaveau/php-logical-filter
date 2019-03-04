<?php
namespace JClaveau\LogicalFilter\Rule;

use       JClaveau\VisibilityViolator;

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
        $this->flushCache();
    }

    /**
     * @return bool
     */
    public function isSimplified()
    {
        return self::simplified == $this->current_simplification_step;
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
        if ( ! isset($this->operands[ $id = $new_operand->getSemanticId() ])) {
            $this->operands[ $id ] = $new_operand;

            if ($this->current_simplification_step) {
                $this->current_simplification_step = null;
            }

            $this->flushCache();
        }

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
     * @param  array|callable $renamings Associative array of renamings or callable
     *                                   that would rename the fields.
     *
     * @return $this
     */
    public function renameFields($renamings)
    {
        $this->renameFields_andReturnIsChanged($renamings);
        return $this;
    }

    /**
     * @param  array|callable $renamings Associative array of renamings or callable
     *                                   that would rename the fields.
     *
     * @return boolean Whether or not the operation changed semantically
     */
    public function renameFields_andReturnIsChanged($renamings)
    {
        $is_changed = false;

        foreach ($this->operands as $operand) {
            if ($operand->renameFields_andReturnIsChanged($renamings)) {
                $is_changed = true;
            }
        }

        if ($is_changed) {
            $this->flushCache();
        }

        // TODO remove this forced cache flushing ONLY when carefully
        // unit tested
        $this->flushCache();
        return $this;
    }

    /**
     * @param string $step_to_go_to
     * @param array  $simplification_options
     * @param bool   $force
     */
    public function moveSimplificationStepForward($step_to_go_to, array $simplification_options, $force=false)
    {
        if ( ! in_array($step_to_go_to, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to go to: ".$step_to_go_to
            );
        }

        // if ($this->isNormalizationAllowed($simplification_options) && !$force && $this->current_simplification_step != null) {
        if ( ! $force && null !== $this->current_simplification_step) {
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
     * @return string The current simplification step
     */
    public function getSimplificationStep()
    {
        return $this->current_simplification_step;
    }

    /**
     * Checks if a simplification step is reached.
     *
     * @param  string $step
     *
     * @return bool
     */
    public function simplicationStepReached($step)
    {
        if ( ! in_array($step, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step: ".$step
            );
        }

        if (null === $this->current_simplification_step) {
            return false;
        }

        $steps_indices = array_flip(self::simplification_steps);

        $current_index = $steps_indices[ $this->current_simplification_step ];
        $step_index    = $steps_indices[ $step ];

        return $current_index >= $step_index;
    }

    /**
     * Replace NotRule objects by the negation of their operands.
     *
     * @return AbstractOperationRule $this or a $new rule with negations removed
     */
    public function removeNegations(array $contextual_options)
    {
        if ( ! $this->isNormalizationAllowed($contextual_options)) {
            return $this;
        }

        $this->moveSimplificationStepForward(self::remove_negations, $contextual_options);

        $new_rule = $this;
        if ($operands = $this->operands) {
            foreach ($operands as $i => $operand) {
                if ($operand instanceof NotRule) {
                    $operands[$i] = $operand->negateOperand($contextual_options);
                }

                if ($operands[$i] instanceof AbstractOperationRule) {
                    $operands[$i]->removeNegations( $contextual_options );
                }
            }

            $new_rule = $this->setOperandsOrReplaceByOperation($operands, $contextual_options);
        }

        return $new_rule;
    }

    /**
     * Operation cleaning consists of removing operation with one operand
     * and removing operations having a same type of operation as operand.
     *
     * This operation has been required between every steps until now.
     *
     * @toopt Trigger cleaning requirement during simplification steps
     *
     * @param  array    $simplification_options
     * @param  bool     $recurse
     *
     * @return AbstractOperationRule
     */
    public function cleanOperations(array $simplification_options, $recurse=true)
    {
        if ($recurse) {
            foreach ($this->operands as $i => $operand) {
                if (     $operand instanceof AbstractOperationRule
                    && ! $operand instanceof InRule
                    && ! $operand instanceof NotEqualRule
                    && ! $operand instanceof NotInRule
                ) {
                    $this->operands[$i] = $operand->cleanOperations($simplification_options);
                }
            }
        }

        if ($this instanceof NotRule) {
            return $this;
        }

        $is_modified = true;
        while ($is_modified) {
            $is_modified = false;

            if ($this->removeMonooperandOperationsOperands($simplification_options)) {
                $is_modified = true;
            }

            if ($this->removeSameOperationOperands($simplification_options)) {
                $is_modified = true;
            }
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
    public function removeMonooperandOperationsOperands(array $simplification_options)
    {
        foreach ($this->operands as $i => $operand) {
            if ( ! $operand instanceof AbstractOperationRule || $operand instanceof NotRule) {
                continue;
            }

            if ($operand instanceof InRule && ! $operand->isNormalizationAllowed($simplification_options)) {
                $count = count($operand->getPossibilities());
            }
            else {
                $count = count($operand->getOperands());
            }

            if (
                    ($operand instanceof AndRule || $operand instanceof OrRule)
                && 1 == $count
            ) {
                $sub_operands       = $operand->getOperands();
                $this->operands[$i] = reset($sub_operands);
                $has_been_changed   = true;
            }
        }

        return ! empty($has_been_changed);
    }

    /**
     * Removes duplicates between the current AbstractOperationRule.
     *
     * @return AbstractOperationRule the simplified rule
     */
    public function unifyAtomicOperands($simplification_strategy_step = false, array $contextual_options)
    {
        if ($simplification_strategy_step) {
            $this->moveSimplificationStepForward( self::unify_atomic_operands, $contextual_options );
        }

        // $this->dump(true);

        if ( ! $this->isNormalizationAllowed($contextual_options)) {
            return $this;
        }

        $operands = $this->getOperands();
        foreach ($operands as &$operand) {
            if ($operand instanceof AbstractOperationRule) {
                $operand = $operand->unifyAtomicOperands($simplification_strategy_step, $contextual_options);
            }
        }

        $class = get_class($this);

        $operandsByFields = $class::groupOperandsByFieldAndOperator_static($operands);
        $operandsByFields = $class::simplifySameOperands($operandsByFields);

        if ($this instanceof AndRule) {
            // unifiying operands of different types
            $operandsByFields = $class::simplifyDifferentOperands($operandsByFields);
        }

        // Remove the index by fields and operators
        $unifiedOperands = [];
        foreach ($operandsByFields as $field => $operandsByOperator) {
            foreach ($operandsByOperator as $operator => $operands) {
                try {
                    $unifiedOperands = array_merge($unifiedOperands, $operands);
                }
                catch (\Exception $e) {
                    VisibilityViolator::setHiddenProperty(
                        $e, 'message',
                        $e->getMessage() . "\n" . var_export($operandsByOperator, true)
                    );

                    throw $e;
                }
            }
        }

        return $this->setOperandsOrReplaceByOperation( $unifiedOperands, $contextual_options );
    }

    private static $simplification_cache = [];

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
    final public function simplify($options=[])
    {
        $step_to_stop_before = ! empty($options['stop_before'])        ? $options['stop_before'] : null;
        $step_to_stop_after  = ! empty($options['stop_after'])         ? $options['stop_after']  : null;
        $force_logical_core  = ! empty($options['force_logical_core']) ? $options['force_logical_core'] : false;

        if ($step_to_stop_before && ! in_array($step_to_stop_before, self::simplification_steps)) {
            throw new \InvalidArgumentException(
                "Invalid simplification step to stop at: ".$step_to_stop_before
            );
        }

        ksort($options);
        $options_id = hash('md4', serialize($options));

        $id = $this->getSemanticId().'-'.$options_id;
        if (isset(self::$simplification_cache[$id])) {
            return self::$simplification_cache[$id]->copy();
        }

        $this->flushCache();

        $cache_keys = [$id];

        // $this->dump(true);
        $this->cleanOperations($options);
        // $this->dump(true);
        $instance = $this->unifyAtomicOperands(false, $options);

        $cache_keys[] = $instance->getSemanticId().'-'.$options_id;

        if (self::remove_negations == $step_to_stop_before) {
            return $instance;
        }

        // $this->dump(!true);
        $instance = $instance->removeNegations($options);

        // $instance->dump(true);

        if (self::remove_negations == $step_to_stop_after ||
            self::rootify_disjunctions == $step_to_stop_before ) {
            return $instance;
        }

        // $instance->dump(true);

        $instance->cleanOperations($options);
        $instance = $instance->rootifyDisjunctions($options);

        // $instance->dump(true);

        if (self::rootify_disjunctions == $step_to_stop_after ||
            self::unify_atomic_operands == $step_to_stop_before ) {
            return $instance;
        }

        if ( ! $instance instanceof AbstractAtomicRule) {
            $instance->cleanOperations($options);
            $instance->unifyAtomicOperands(true, $options);

            // $instance->dump(true);

            if (self::unify_atomic_operands == $step_to_stop_after ||
                self::remove_invalid_branches == $step_to_stop_before ) {
                return $instance;
            }

            $instance->cleanOperations($options);
            if (method_exists($instance, 'removeInvalidBranches')) {
                $instance->removeInvalidBranches($options);
            }
        }

        // $instance->dump(true);
        $instance->cleanOperations($options);

        // the root rule cannot be cleaned so we wrap it and apply a
        // last non recursive clean
        // TODO kind of monad|become|cese
        // @see https://github.com/jclaveau/php-logical-filter/issues/20
        if ($instance instanceof AndRule || $instance instanceof OrRule ) {
            if ( ! $instance->getOperands()) {
                return $instance;
            }

            $operands = (new AndRule([$instance]))
                ->cleanOperations($options, false)
                // ->dump(true)
                ->getOperands();

            if (1 == count($operands)) {
                $instance = reset($operands);
            }
        }

        if ($force_logical_core) {
            $instance = $instance->addMinimalCase();
        }

        $cache_keys[] = $instance->getSemanticId().'-'.$options_id;
        foreach ($cache_keys as $cache_key) {
            self::$simplification_cache[ $cache_key ] = $instance;
        }

        return $instance->copy();
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

            // For FilteredValue and FilteredKey
            $field = (string) $field;

            if ( ! isset($operandsByFields[ $field ])) {
                $operandsByFields[ $field ] = [];
            }

            if ( ! isset($operandsByFields[ $field ][ $operand::operator ])) {
                $operandsByFields[ $field ][ $operand::operator ] = [];
            }

            $operandsByFields[ $field ][ $operand::operator ][] = $operand;
        }

        return $operandsByFields;
    }

    /**
     * Indexes operands by their fields and operators. This sorting is
     * used during the simplification step.
     *
     * @return array The 3 dimensions array of operands: field > operator > i
     */
    protected static function groupOperandsByFieldAndOperator_static($operands)
    {
        $operandsByFields = [];
        foreach ($operands as $operand) {

            // Operation rules have no field but we need to keep them anyway
            $field = method_exists($operand, 'getField') ? $operand->getField() : '';

            // For FilteredValue and FilteredKey
            $field = (string) $field;

            if ( ! isset($operandsByFields[ $field ])) {
                $operandsByFields[ $field ] = [];
            }

            if ( ! isset($operandsByFields[ $field ][ $operand::operator ])) {
                $operandsByFields[ $field ][ $operand::operator ] = [];
            }

            $operandsByFields[ $field ][ $operand::operator ][] = $operand;
        }

        return $operandsByFields;
    }

    /**
     * Clones the rule and its operands.
     *
     * @return AbstractOperationRule A copy of the current instance with copied operands.
     */
    final public function copy()
    {
        return clone $this;
    }

    /**
     * Make a deep copy of operands
     */
    public function __clone()
    {
        foreach ($this->operands as $operand_id => &$operand) {
            $this->operands[$operand_id] = $operand->copy();
        }
    }

    /**
     */
    public function isNormalizationAllowed(array $current_simplification_options)
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
        if (isset($operands[$index])) {
            return $operands[$index];
        }
    }

    /**/
}
