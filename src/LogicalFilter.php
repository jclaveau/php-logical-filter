<?php
/**
 * LogicalFilter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter;

use JClaveau\LogicalFilter\Rule\AbstractRule;
use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\NotRule;

use JClaveau\LogicalFilter\Filterer\Filterer;
use JClaveau\LogicalFilter\Filterer\PhpFilterer;
use JClaveau\LogicalFilter\Filterer\CustomizableFilterer;
use JClaveau\LogicalFilter\Filterer\RuleFilterer;

use JClaveau\VisibilityViolator\VisibilityViolator;

/**
 * LogicalFilter describes a set of logical rules structured by
 * conjunctions and disjunctions (AND and OR).
 *
 * It's able to simplify them in order to find contractories branches
 * of the tree rule and check if there is at least one set rules having
 * possibilities.
 */
class LogicalFilter implements \JsonSerializable
{
    /** @var  AndRule $rules */
    protected $rules;

    /** @var  Filterer $default_filterer */
    protected $default_filterer;

    /**
     * Creates a filter. You can provide a description of rules as in
     * addRules() as paramater.
     *
     * @param  array    $rules
     * @param  Filterer $default_filterer
     *
     * @see self::addRules
     */
    public function __construct(array $rules=[], Filterer $default_filterer=null)
    {
        if ($rules)
            $this->and_( $rules );

        if ($default_filterer)
            $this->default_filterer = $default_filterer;
    }

    /**
     */
    protected function getDefaultFilterer()
    {
        if (!$this->default_filterer)
            $this->default_filterer = new PhpFilterer();

        return $this->default_filterer;
    }

    /**
     * This method parses different ways to define the rules of a LogicalFilter.
     * + You can add N already instanciated Rules.
     * + You can provide 3 arguments: $field, $operator, $value
     * + You can provide a tree of rules:
     * [
     *      'or',
     *      [
     *          'and',
     *          ['field_5', 'above', 'a'],
     *          ['field_5', 'below', 'a'],
     *      ],
     *      ['field_6', 'equal', 'b'],
     *  ]
     *
     * @param  string        $operation         and | or
     * @param  array         $rules_description Rules description
     * @return LogicalFilter $this
     */
    protected function addRules( $operation, array $rules_description )
    {
        if (    count($rules_description) == 3
            &&  is_string($rules_description[0])
            &&  is_string($rules_description[1])
        ) {
            // Atomic rules
            $new_rule = AbstractRule::generateSimpleRule(
                $rules_description[0], // field
                $rules_description[1], // operator
                $rules_description[2]  // value
            );

            $this->addRule($new_rule, $operation);
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof LogicalFilter;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $filter) {
                $this->addRule( $filter->getRules(), $operation);
            }
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof AbstractRule;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $new_rule) {
                $this->addRule( $new_rule, $operation);
            }
        }
        elseif (count($rules_description) == 1 && is_array($rules_description[0])) {
            $fake_root = new AndRule;

            $this->addCompositeRule_recursion(
                $rules_description[0],
                $fake_root
            );

            $this->addRule( $fake_root->getOperands()[0], $operation );
        }
        else {
            throw new \InvalidArgumentException(
                "Bad set of arguments provided for rules addition: "
                .var_export($rules_description, true)
            );
        }

        return $this;
    }

    /**
     * Add one rule object to the filter
     *
     * @param AbstractRule $rule
     * @param string       $operation
     *
     * @return $this
     */
    protected function addRule( AbstractRule $rule, $operation=AndRule::operator )
    {
        if ( ($this->rules instanceof AndRule || $this->rules instanceof OrRule)
            && !$this->rules->getOperands() ) {
            throw new \LogicException(
                 "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have already been simplified: "
                .$this->rules
            );
        }

        if ($this->rules === null) {
            $this->rules = $rule;
        }
        elseif (($tmp_rules = $this->rules) // $this->rules::operator not supported in PHP 5.6
            &&  ($tmp_rules::operator != $operation)) {

            if ($operation == AndRule::operator) {
                $this->rules = new AndRule([$this->rules, $rule]);
            }
            elseif ($operation == OrRule::operator) {
                $this->rules = new OrRule([$this->rules, $rule]);
            }
            else {
                throw new \InvalidArgumentException(
                    "\$operation must be '".AndRule::operator."' or '".OrRule::operator
                    ."' instead of: ".var_export($operation, true)
                );
            }
        }
        else {
            $this->rules->addOperand($rule);
        }

        return $this;
    }

    /**
     * Recursion auxiliary of addCompositeRule.
     *
     * @param array                 $rules_composition  The description of the
     *                                                  rules to add.
     * @param AbstractOperationRule $recursion_position The position in the
     *                                                  tree where rules must
     *                                                  be added.
     *
     * @return $this
     */
    protected function addCompositeRule_recursion(
        array $rules_composition,
        AbstractOperationRule $recursion_position
    ) {
        if (!array_filter($rules_composition, function ($rule_composition_part) {
            return is_string($rule_composition_part);
        })) {
            // at least one operator is required for operation rules
            throw new \InvalidArgumentException(
                "Please provide an operator for the operation: \n"
                .var_export($rules_composition, true)
            );
        }
        elseif (    count($rules_composition) == 3
            &&  !in_array( AndRule::operator, $rules_composition, true )
            &&  !in_array( OrRule::operator,  $rules_composition, true )
            &&  !in_array( NotRule::operator, $rules_composition, true )
            &&  !in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition, true )
            &&  !in_array( AbstractRule::findSymbolicOperator( OrRule::operator ),  $rules_composition, true )
            &&  !in_array( AbstractRule::findSymbolicOperator( NotRule::operator ), $rules_composition, true )
        ) {
            // atomic or composit rules
            $operand_left  = $rules_composition[0];
            $operation     = $rules_composition[1];
            $operand_right = $rules_composition[2];

            $rule = AbstractRule::generateSimpleRule(
                $operand_left, $operation, $operand_right
            );
            $recursion_position->addOperand( $rule );
        }
        else {
            // operations
            if (    $rules_composition[0] == NotRule::operator
                ||  $rules_composition[0] == AbstractRule::findSymbolicOperator( NotRule::operator ) ) {
                $rule = new NotRule();
            }
            elseif (in_array( AndRule::operator, $rules_composition )
                ||  in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition )) {
                $rule = new AndRule();
            }
            elseif (in_array( OrRule::operator, $rules_composition )
                ||  in_array( AbstractRule::findSymbolicOperator( OrRule::operator ), $rules_composition ) ) {
                $rule = new OrRule();
            }
            else {
                throw new \InvalidArgumentException(
                    "A rule description seems to be an operation but do "
                    ."not contains a valid operator: ".var_export($rules_composition, true)
                );
            }

            $operator = $rule::operator;

            $operands_descriptions = array_filter(
                $rules_composition,
                function ($operand) use ($operator) {
                    return !in_array($operand, [$operator, AbstractRule::findSymbolicOperator( $operator )]);
                }
            );

            $remaining_operations = array_filter(
                $operands_descriptions,
                function($operand) {
                    return !is_array($operand);
                }
            );

            if ($remaining_operations) {
                throw new \InvalidArgumentException(
                    "Mixing different operations in the same rule level not implemented: \n["
                    . implode(', ', $remaining_operations)."]\n"
                    . 'in ' . var_export($rules_composition, true)
                );
            }

            $recursion_position->addOperand( $rule );

            if ($operator == NotRule::operator && count($operands_descriptions) != 1) {
                throw new \InvalidArgumentException(
                    "Negations can have only one operand: \n"
                    .var_export($rules_composition, true)
                );
            }

            foreach ($operands_descriptions as $operands_description) {
                $this->addCompositeRule_recursion(
                    $operands_description,
                    $rule
                );
            }
        }

        return $this;
    }

    /**
     * This method parses different ways to define the rules of a LogicalFilter
     * and add them as a new And part of the filter.
     * + You can add N already instanciated Rules.
     * + You can provide 3 arguments: $field, $operator, $value
     * + You can provide a tree of rules:
     * [
     *      'or',
     *      [
     *          'and',
     *          ['field_5', 'above', 'a'],
     *          ['field_5', 'below', 'a'],
     *      ],
     *      ['field_6', 'equal', 'b'],
     *  ]
     *
     * @param  mixed The descriptions of the rules to add
     * @return $this
     *
     * @todo remove the _ for PHP 7
     */
    public function and_()
    {
        $this->addRules( AndRule::operator, func_get_args());
        return $this;
    }

    /**
     * This method parses different ways to define the rules of a LogicalFilter
     * and add them as a new Or part of the filter.
     * + You can add N already instanciated Rules.
     * + You can provide 3 arguments: $field, $operator, $value
     * + You can provide a tree of rules:
     * [
     *      'or',
     *      [
     *          'and',
     *          ['field_5', 'above', 'a'],
     *          ['field_5', 'below', 'a'],
     *      ],
     *      ['field_6', 'equal', 'b'],
     *  ]
     *
     * @param  mixed The descriptions of the rules to add
     * @return $this
     *
     * @todo
     * @todo remove the _ for PHP 7
     */
    public function or_()
    {
        if ($this->rules !== null)
            $this->addRules( OrRule::operator, func_get_args());
        return $this;
    }

    /**
     * Retrieve all the rules.
     *
     * @param  bool $copy By default copy the rule tree to avoid side effects.
     *
     * @return AbstractRule The tree of rules
     */
    public function getRules($copy = true)
    {
        return $copy && $this->rules ? $this->rules->copy() : $this->rules;
    }

    /**
     * Remove any constraint being a duplicate of another one.
     *
     * @param  array $options stop_after | stop_before |
     * @return $this
     */
    public function simplify($options=[])
    {
        if ($this->rules) {
            // AndRule added to make all Operation methods available
            $this->rules = (new AndRule([$this->rules]))
                ->simplify( $options )
                // ->dump(true, false)
                ;
        }

        return $this;
    }

    /**
     * Checks if there is at least on set of conditions which is not
     * contradictory.
     *
     * Checking if a filter has solutions require to simplify it first.
     * To let the control on the balance between readability and
     * performances, the required simplification can be saved or not
     * depending on the $save_simplification parameter.
     *
     * @param  $save_simplification
     *
     * @return bool
     */
    public function hasSolution($save_simplification=true)
    {
        if (!$this->rules)
            return true;

        if ($save_simplification) {
            $this->simplify();
            return $this->rules->hasSolution();
        }

        return $this->copy()->simplify()->rules->hasSolution();
    }

    /**
     * Returns an array describing the rule tree of the Filter.
     *
     * @param $debug Provides a source oriented dump.
     *
     * @return array A description of the rules.
     */
    public function toArray($debug=false)
    {
        return $this->rules ? $this->rules->toArray($debug) : $this->rules;
    }

    /**
     * Returns an array describing the rule tree of the Filter.
     *
     * @param $debug Provides a source oriented dump.
     *
     * @return array A description of the rules.
     */
    public function toString(array $options=[])
    {
        return $this->rules ? $this->rules->toString($options) : $this->rules;
    }

    /**
     * For implementing JsonSerializable interface.
     *
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Removes all the defined rules.
     *
     * @return $this
     */
    public function flushRules()
    {
        $this->rules = null;
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
        if (method_exists($this->rules, 'renameField'))
            $this->rules->renameField($renamings);
        elseif ($this->rules)
            $this->rules->renameFields($renamings);

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return string $this
     */
    public function removeRules($filter)
    {
        $this->rules = (new RuleFilterer)->apply(
            new LogicalFilter($filter),
            $this->rules,
            [
                Filterer::on_row_matches => function($rule, $key, &$rows, $matching_case) {
                    // $rule->dump();
                    // $matching_case->dump(true);
                    unset( $rows[$key] );
                },
                Filterer::on_row_mismatches => function($rule, $key, &$rows, $matching_case) {
                    // $rule->dump();
                    // $matching_case && $matching_case->dump(true);
                }
            ]
        );

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return array The rules matching the filter
     * @return array $options debug | leafs_only | clean_empty_branches
     *
     *
     * @todo Merge with rules
     */
    public function keepLeafRulesMatching($filter=[], array $options=[])
    {
        $clean_empty_branches = !isset($options['clean_empty_branches']) || $options['clean_empty_branches'];

        $filter = (new LogicalFilter($filter, new RuleFilterer))
        // ->dump()
        ;

        $options['leafs_only'] = true;

        $this->rules = (new RuleFilterer)->apply($filter, $this->rules, $options);
        // $this->rules->dump(true);


        // clean the remaining branches
        if ($clean_empty_branches) {
            $this->rules = (new RuleFilterer)->apply(
                new LogicalFilter(['and',
                    ['operator', 'in', ['or', 'and', 'not', '!in']],
                    ['children', '=', 0],
                ]),
                $this->rules,
                [
                    Filterer::on_row_matches => function($rule, $key, &$rows) {
                        unset( $rows[$key] );
                    },
                    Filterer::on_row_mismatches => function($rule, $key, &$rows) {
                    }
                ]
            );

            // TODO replace it by a FalseRule
            if ($this->rules === false)
                $this->rules = new AndRule;
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return array The rules matching the filter
     *
     *
     * @todo Merge with rules
     */
    public function listLeafRulesMatching($filter=[])
    {
        $filter = (new LogicalFilter($filter, new RuleFilterer))
        // ->dump()
        ;

        if (!$this->rules)
            return [];

        $out = [];
        (new RuleFilterer)->apply(
            $filter,
            $this->rules,
            [
                Filterer::on_row_matches => function(
                    AbstractRule $matching_rule,
                    $key,
                    array $siblings
                ) use (&$out) {

                    if (    !$matching_rule instanceof AndRule
                        &&  !$matching_rule instanceof OrRule
                        &&  !$matching_rule instanceof NotRule
                    ) {
                        $out[] = $matching_rule;
                    }
                }
            ]
        );

        return $out;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return array The rules matching the filter
     *
     *
     * @todo Merge with rules
     */
    public function onEachRule($filter=[], $options)
    {
        $filter = (new LogicalFilter($filter, new RuleFilterer))
        // ->dump()
        ;

        if (!$this->rules)
            return [];

        if (is_callable($options)) {
            $options = [
                Filterer::on_row_matches => $options,
            ];
        }

        (new RuleFilterer)->apply(
            $filter,
            $this->rules,
            $options
        );

        return $this;
    }

    /**
     * Clone the current object and its rules.
     *
     * @return LogicalFilter A copy of the current instance with a copied ruletree
     */
    public function copy()
    {
        $newFilter = clone $this;

        if ($this->rules instanceof AbstractRule) {
            VisibilityViolator::setHiddenProperty(
                $newFilter, 'rules', $this->rules->copy()
            );
        }

        return $newFilter;
    }

    /**
     */
    public function dump($exit=false, $debug=false, $callstack_depth = 2)
    {
        if ($this->rules) {
            $this->rules->dump($exit, $debug, 3);
        }
        else {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
            $caller = $bt[ $callstack_depth - 2 ];

            echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
            var_export($this->toArray($debug));
            echo "\n\n";
            if ($exit)
                exit;
        }

        return $this;
    }

    /**
     * Applies the current instance to a set of data.
     *
     * @param  mixed                  $data_to_filter
     * @param  Filterer|callable|null $filterer
     *
     * @return mixed The filtered data
     */
    public function applyOn($data_to_filter, $action_on_matches=null, $filterer=null)
    {
        if (!$filterer) {
            $filterer = $this->getDefaultFilterer();
        }
        elseif (is_callable($filterer)) {
            $filterer = new CustomizableFilterer($filterer);
        }
        elseif (!$filterer instanceof Filterer) {
            throw new \InvalidArgumentException(
                 "The given \$filterer must be null or a callable or a instance "
                ."of Filterer instead of: ".var_export($filterer, true)
            );
        }

        if ($data_to_filter instanceof LogicalFilter) {
            $filtered_rules = $filterer->apply( $this, $data_to_filter->getRules() );
            return $data_to_filter->flushRules()->addRule( $filtered_rules );
        }
        else {
            return $filterer->apply( $this, $data_to_filter );
        }
    }

    /**/
}
