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

    /** @var  array $options */
    protected $options = [];

    /** @var  array $default_options */
    protected static $default_options = [
        'in.normalization_threshold' => 0,
    ];

    /**
     * Creates a filter. You can provide a description of rules as in
     * addRules() as paramater.
     *
     * @param  array    $rules
     * @param  Filterer $default_filterer
     *
     * @see self::addRules
     */
    public function __construct($rules=[], Filterer $default_filterer=null, array $options=[])
    {
        if ($rules instanceof AbstractRule) {
            $rules = $rules->copy();
        }
        elseif ( ! is_null($rules) && ! is_array($rules)) {
            throw new \InvalidArgumentException(
                "\$rules must be a rules description or an AbstractRule instead of"
                .var_export($rules, true)
            );
        }

        if ($default_filterer) {
            $this->default_filterer = $default_filterer;
        }

        if ($options) {
            $this->options = $options;
        }

        if ($rules) {
            $this->and_( $rules );
        }
    }

    /**
     */
    protected function getDefaultFilterer()
    {
        if (! $this->default_filterer) {
            $this->default_filterer = new PhpFilterer();
        }

        return $this->default_filterer;
    }

    /**
     */
    public static function setDefaultOptions(array $options)
    {
        foreach ($options as $name => $default_value) {
            self::$default_options[$name] = $default_value;
        }

        AbstractRule::flushStaticCache();
    }

    /**
     * @return array
     */
    public static function getDefaultOptions()
    {
        return self::$default_options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = self::$default_options;
        foreach ($this->options as $name => $value) {
            $options[$name] = $value;
        }

        return $options;
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
    protected function addRules($operation, array $rules_description)
    {
        if ($rules_description == [null]) {
            // TODO this is due to the bad design of using "Null" instead of
            // TrueRule when a Filter "has no rule". So it's the equivalent of
            // "and true" or "or true".
            // Remove it while fixing https://github.com/jclaveau/php-logical-filter/issues/59
            if (AndRule::operator == $operation) {
                // A && True <=> A
                return $this;
            }
            elseif (OrRule::operator == $operation) {
                // A || True <=> True
                $this->rules = null;
                return $this;
            }
            else {
                throw new InvalidArgumentException(
                    "Unhandled operation '$operation'"
                );
            }
        }

        if (   3 == count($rules_description)
            && is_string($rules_description[0])
            && is_string($rules_description[1])
        ) {
            // Atomic rules
            $new_rule = AbstractRule::generateSimpleRule(
                $rules_description[0], // field
                $rules_description[1], // operator
                $rules_description[2], // value
                $this->getOptions()
            );

            $this->addRule($new_rule, $operation);
        }
        elseif (count($rules_description) == count(array_filter($rules_description, function($arg) {
            return $arg instanceof LogicalFilter;
        })) ) {
            // Already instanciated rules
            foreach ($rules_description as $i => $filter) {
                $rules = $filter->getRules();
                if (null !== $rules) {
                    $this->addRule( $rules, $operation);
                }
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
        elseif (1 == count($rules_description) && is_array($rules_description[0])) {
            if (count($rules_description[0]) == count(array_filter($rules_description[0], function($arg) {
                return $arg instanceof AbstractRule;
            })) ) {
                // Case of $filter->or_([AbstractRule, AbstractRule, AbstractRule, ...])
                foreach ($rules_description[0] as $i => $new_rule) {
                    $this->addRule( $new_rule, $operation );
                }
            }
            else {
                $fake_root = new AndRule;

                $this->addCompositeRule_recursion(
                    $rules_description[0],
                    $fake_root
                );

                $this->addRule($fake_root->getOperands()[0], $operation);
            }
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
        if ($this->rules && in_array( get_class($this->rules), [AndRule::class, OrRule::class])
            && ! $this->rules->getOperands() ) {
            throw new \LogicException(
                 "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have already been simplified: "
                .$this->rules
            );
        }

        if (null === $this->rules) {
            $this->rules = $rule;
        }
        elseif (($tmp_rules = $this->rules) // $this->rules::operator not supported in PHP 5.6
            && ($tmp_rules::operator != $operation)
        ) {
            if (AndRule::operator == $operation) {
                $this->rules = new AndRule([$this->rules, $rule]);
            }
            elseif (OrRule::operator == $operation) {
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
        if ( ! array_filter($rules_composition, function ($rule_composition_part) {
            return is_string($rule_composition_part);
        })) {
            // at least one operator is required for operation rules
            throw new \InvalidArgumentException(
                "Please provide an operator for the operation: \n"
                .var_export($rules_composition, true)
            );
        }
        elseif ( 3 == count($rules_composition)
            && ! in_array( AndRule::operator, $rules_composition, true )
            && ! in_array( OrRule::operator, $rules_composition, true )
            && ! in_array( NotRule::operator, $rules_composition, true )
            && ! in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition, true )
            && ! in_array( AbstractRule::findSymbolicOperator( OrRule::operator ), $rules_composition, true )
            && ! in_array( AbstractRule::findSymbolicOperator( NotRule::operator ), $rules_composition, true )
        ) {
            // atomic or composit rules
            $operand_left  = $rules_composition[0];
            $operation     = $rules_composition[1];
            $operand_right = $rules_composition[2];

            $rule = AbstractRule::generateSimpleRule(
                $operand_left, $operation, $operand_right, $this->getOptions()
            );
            $recursion_position->addOperand( $rule );
        }
        else {
            // operations
            if (   NotRule::operator == $rules_composition[0]
                || $rules_composition[0] == AbstractRule::findSymbolicOperator( NotRule::operator ) ) {
                $rule = new NotRule();
            }
            elseif (in_array( AndRule::operator, $rules_composition )
                || in_array( AbstractRule::findSymbolicOperator( AndRule::operator ), $rules_composition )) {
                $rule = new AndRule();
            }
            elseif (in_array( OrRule::operator, $rules_composition )
                || in_array( AbstractRule::findSymbolicOperator( OrRule::operator ), $rules_composition ) ) {
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
                    return ! in_array($operand, [$operator, AbstractRule::findSymbolicOperator( $operator )]);
                }
            );

            $non_true_rule_descriptions = array_filter(
                $operands_descriptions,
                function($operand) {
                    return null !== $operand  // no rule <=> true
                        || true !== $operand
                        ;
                }
            );

            foreach ($operands_descriptions as $i => $operands_description) {
                if (false === $operands_description) {
                    $operands_descriptions[ $i ] = ['and']; // FalseRule hack
                }
                elseif (null === $operands_description || true === $operands_description) {
                    $operands_description = ['and'];
                    if (empty($non_true_rule_descriptions)) {
                        throw new \LogicException(
                            "TrueRules are not implemented. Please add "
                            ."them to operations having other type of rules"
                        );
                    }

                    unset($operands_descriptions[ $i ]);
                }
            }

            $remaining_operations = array_filter(
                $operands_descriptions,
                function($operand) {
                    return ! is_array($operand)
                        && ! $operand instanceof AbstractRule
                        && ! $operand instanceof LogicalFilter
                        ;
                }
            );

            if ( ! empty($remaining_operations)) {
                throw new \InvalidArgumentException(
                    "Mixing different operations in the same rule level not implemented: \n["
                    . implode(', ', $remaining_operations)."]\n"
                    . 'in ' . var_export($rules_composition, true)
                );
            }

            if (NotRule::operator == $operator && 1 != count($operands_descriptions)) {
                throw new \InvalidArgumentException(
                    "Negations can have only one operand: \n"
                    .var_export($rules_composition, true)
                );
            }

            foreach ($operands_descriptions as $operands_description) {
                if ($operands_description instanceof AbstractRule) {
                    $rule->addOperand($operands_description);
                }
                elseif ($operands_description instanceof LogicalFilter) {
                    $rule->addOperand($operands_description->getRules());
                }
                else {
                    $this->addCompositeRule_recursion(
                        $operands_description,
                        $rule
                    );
                }
            }

            $recursion_position->addOperand( $rule );
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
        $this->addRules( OrRule::operator, func_get_args());
        return $this;
    }

    /**
     * @deprecated
     */
    public function matches($rules_to_match)
    {
        return $this->hasSolutionIf($rules_to_match);
    }

    /**
     * Checks that a filter matches another one.
     *
     * @param array|AbstractRule $rules_to_match
     *
     * @return bool Whether or not this combination of filters has
     *              potential solutions
     */
    public function hasSolutionIf($rules_to_match)
    {
        return $this
            ->copy()
            ->and_($rules_to_match)
            ->hasSolution()
            ;
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
        if ($this->rules) {
            $this->rules = $this->rules->addMinimalCase();
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
        if (! $this->rules) {
            return true;
        }

        if ($save_simplification) {
            $this->simplify();
            return $this->rules->hasSolution();
        }

        return $this->copy()->simplify()->rules->hasSolution();
    }

    /**
     * Returns an array describing the rule tree of the Filter.
     *
     * @param array $options
     *
     * @return array A description of the rules.
     */
    public function toArray(array $options=[])
    {
        return $this->rules ? $this->rules->toArray($options) : $this->rules;
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
     * Returns a unique id corresponding to the set of rules of the filter
     *
     * @return string The unique semantic id
     */
    public function getSemanticId()
    {
        return $this->rules ? $this->rules->getSemanticId() : null;
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
     * @see    https://secure.php.net/manual/en/language.oop5.magic.php#object.invoke
     * @param  mixed $row
     * @return bool
     */
    public function __invoke($row, $key=null)
    {
        return $this->validates($row, $key);
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
     * @return LogicalFilter  $this
     */
    public function renameFields($renamings)
    {
        if (method_exists($this->rules, 'renameField')) {
            $this->rules->renameField($renamings);
        }
        elseif ($this->rules) {
            $this->rules->renameFields($renamings);
        }

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
        $cache_flush_required = false;

        $this->rules = (new RuleFilterer)->apply(
            new LogicalFilter($filter),
            $this->rules,
            [
                Filterer::on_row_matches => function($rule, $key, &$rows, $matching_case) use (&$cache_flush_required) {
                    // $rule->dump();
                    unset( $rows[$key] );
                    if ( ! $rows ) {
                        throw new \Exception(
                             "Removing the only rule $rule from the filter $this "
                            ."produces a case which has no possible solution due to missing "
                            ."implementation of TrueRule.\n"
                            ."Please see: https://github.com/jclaveau/php-logical-filter/issues/59"
                        );
                    }

                    // $matching_case->dump(true);
                    $cache_flush_required = true;
                },
                // Filterer::on_row_mismatches => function($rule, $key, &$rows, $matching_case) {
                    // $rule->dump();
                    // $matching_case && $matching_case->dump(true);
                // }
            ]
        );

        if ($cache_flush_required) {
            $this->rules->flushCache();
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return array The rules matching the filter
     * @return array $options debug | leaves_only | clean_empty_branches
     *
     *
     * @todo Merge with rules
     */
    public function keepLeafRulesMatching($filter=[], array $options=[])
    {
        $clean_empty_branches = ! isset($options['clean_empty_branches']) || $options['clean_empty_branches'];

        $filter = (new LogicalFilter($filter, new RuleFilterer))
        // ->dump()
        ;

        $options[ Filterer::leaves_only ] = true;

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
                        unset($rows[$key]);
                    },
                    Filterer::on_row_mismatches => function($rule, $key, &$rows) {
                    },
                ]
            );

            // TODO replace it by a FalseRule
            if (false === $this->rules) {
                $this->rules = new AndRule;
            }
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

        if (! $this->rules) {
            return [];
        }

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
                    if (   ! $matching_rule instanceof AndRule
                        && ! $matching_rule instanceof OrRule
                        && ! $matching_rule instanceof NotRule
                    ) {
                        $out[] = $matching_rule;
                    }
                },
                Filterer::leaves_only => true,
            ]
        );

        return $out;
    }

    /**
     * $filter->onEachRule(
     *      ['field', 'in', [...]],
     *      function ($rule, $key, array &$rules) {
     *          // ...
     * })
     *
     * $filter->onEachRule(
     *      ['field', 'in', [...]],
     *      [
     *          Filterer::on_row_matches => function ($rule, $key, array &$rules) {
     *              // ...
     *          },
     *          Filterer::on_row_mismatches => function ($rule, $key, array &$rules) {
     *              // ...
     *          },
     *      ]
     * )
     *
     * @todo Make it available on AbstractRule also
     *
     * @param  array|LogicalFilter
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return array          The rules matching the filter
     */
    public function onEachRule($filter=[], $options)
    {
        $filter = (new LogicalFilter($filter, new RuleFilterer))
        // ->dump()
        ;

        if (! $this->rules) {
            return [];
        }

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
     * $filter->onEachCase(function (AndRule $case, $key, array &$caseRules) {
     *      // do whatever you want on the current case...
     * })
     *
     * @param  array|callable $action Callback to apply on each case.
     * @return LogicalFilter  $this
     *
     * @todo Make it available on AbstractRule also
     */
    public function onEachCase(callable $action)
    {
        $this->simplify()->addMinimalCase();

        if (! $this->rules) {
            return $this;
        }

        $operands = $this->rules->getOperands();

        foreach ($operands as $i => &$and_case) {
            $arguments = [
                &$and_case,
            ];
            call_user_func_array($action, $arguments);
        }

        $this->rules = new OrRule($operands);

        return $this;
    }

    /**
     * Retrieves the minimum possibility and the maximum possibility for
     * each field of the rules matching the filter.
     *
     * @param  array|LogicalFilter|AbstractRule $ruleFilter
     *
     * @return array The bounds of the range and a nullable property for each field
     */
    public function getRanges($ruleFilter=null)
    {
        $ranges = [];

        $this->onEachCase(function (AndRule $and_rule) use (&$ranges, $ruleFilter) {
            (new self($and_rule))->onEachRule(
                ['and',
                    $ruleFilter,
                    ['operator', 'in', [
                        '=', '>', '<', '>=', '<=',
                        '><', '><=', '=><=', '=><',
                    ]],
                ],
                function ($rule) use (&$ranges) {

                    $field = $rule->getField();

                    $range = isset($ranges[ $field ])
                           ? $ranges[ $field ]
                           : ['min' => [], 'max' => [], 'nullable' => false];

                    if ($rule::operator == '=') {
                        if (null === $rule->getValues()) {
                            $range['nullable'] = true;
                        }
                        else {
                            $range['min'][] = $rule->getValues();
                            $range['max'][] = $rule->getValues();
                        }
                    }
                    elseif (in_array($rule::operator, ['<', '<='])) {
                        $range['max'][] = $rule->getValues();
                    }
                    elseif (in_array($rule::operator, ['>', '>='])) {
                        $range['min'][] = $rule->getValues();
                    }
                    elseif (in_array($rule::operator, ['><', '><=', '=><=', '=><'])) {
                        $range['min'][] = $rule->getValues()[0];
                        $range['max'][] = $rule->getValues()[1];
                    }
                    else {
                        throw new \LogicException(
                            "Buggy case: ".$rule::operator
                        );
                    }

                    $ranges[ $field ] = $range;
                }
            );
        });

        foreach ($ranges as &$range) {
            $range['min'] = min($range['min']);
            $range['max'] = max($range['max']);
        }

        return $ranges;
    }

    /**
     * Retrieves the minimum possibility and the maximum possibility for
     * the given field.
     *
     * @param  mixed $field
     * @return array The bounds of the range and a nullable property for the given field
     */
    public function getFieldRange($field)
    {
        $range = $this->getRanges(['field', '=', $field]);
        return isset($range[$field])
            ? $range[$field]
            : ['min' => null, 'max' => null, 'nullable' => false];
    }

    /**
     * Clone the current object and its rules.
     *
     * @return LogicalFilter A copy of the current instance with a copied ruletree
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Make a deep copy of the rules
     */
    public function __clone()
    {
        if ($this->rules) {
            $this->rules = $this->rules->copy();
        }
    }

    /**
     * Copy the current instance into the variable given as parameter
     * and returns the copy.
     *
     * @return LogicalFilter
     */
    public function saveAs( &$variable)
    {
        return $variable = $this;
    }

    /**
     * Copy the current instance into the variable given as parameter
     * and returns the copied instance.
     *
     * @return LogicalFilter
     */
    public function saveCopyAs( &$copied_variable)
    {
        $copied_variable = $this->copy();
        return $this;
    }

    /**
     * @param bool  $exit=false
     * @param array $options    + callstack_depth=2 The level of the caller to dump
     *                          + mode='string' in 'export' | 'dump' | 'string'
     *
     * @return $this
     */
    public function dump($exit=false, array $options=[])
    {
        $default_options = [
            'callstack_depth' => 3,
            'mode'            => 'string',
        ];
        foreach ($default_options as $default_option => &$default_value) {
            if ( ! isset($options[ $default_option ])) {
                $options[ $default_option ] = $default_value;
            }
        }
        extract($options);

        if ($this->rules) {
            $this->rules->dump($exit, $options);
        }
        else {
            // TODO dump a TrueRule
            $bt     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
            $caller = $bt[ $callstack_depth - 2 ];

            // get line and file from the previous level of the caller
            // TODO go deeper if this case exist?
            if (! isset($caller['file'])) {
                $caller['file'] = $bt[ $callstack_depth - 3 ]['file'];
            }

            if (! isset($caller['line'])) {
                $caller['line'] = $bt[ $callstack_depth - 3 ]['line'];
            }

            try {
                echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
                var_export($this->toArray($options));
            }
            catch (\Exception $e) {
                echo "\nError while dumping: " . $e->getMessage() . "\n";
                var_export($caller);
                echo "\n\n";
                var_export($bt);
                echo "\n\n";
                var_export($this->toArray($options));
            }
            echo "\n\n";

            if ($exit) {
                exit;
            }
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
        if (! $filterer) {
            $filterer = $this->getDefaultFilterer();
        }
        elseif (is_callable($filterer)) {
            $filterer = new CustomizableFilterer($filterer);
        }
        elseif (! $filterer instanceof Filterer) {
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
            return $filterer->apply($this, $data_to_filter);
        }
    }

    /**
     * Applies the current instance to a value (and its index optionnally).
     *
     * @param  mixed                  $value_to_check
     * @param  scalar                 $index
     * @param  Filterer|callable|null $filterer
     *
     * @return AbstractRule|false|true + False if the filter doesn't validates
     *                                 + Null if the target has no sens (operation filtered by field for example)
     *                                 + A rule tree containing the first matching case if there is one.
     */
    public function validates($value_to_check, $key_to_check=null, $filterer=null)
    {
        if (! $filterer) {
            $filterer = $this->getDefaultFilterer();
        }
        elseif (is_callable($filterer)) {
            $filterer = new CustomizableFilterer($filterer);
        }
        elseif (! $filterer instanceof Filterer) {
            throw new \InvalidArgumentException(
                 "The given \$filterer must be null or a callable or a instance "
                ."of Filterer instead of: ".var_export($filterer, true)
            );
        }

        return $filterer->hasMatchingCase($this, $value_to_check, $key_to_check);
    }

    /**/
}
