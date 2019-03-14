<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\NotRule;
use JClaveau\LogicalFilter\Rule\InRule;
use JClaveau\LogicalFilter\Rule\EqualRule;
use JClaveau\LogicalFilter\Rule\AboveRule;
use JClaveau\LogicalFilter\Rule\BelowRule;

trait LogicalFilterTest_rules_descriptions
{
    /**
     */
    public function test_add_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '=', 2]
        );

        $this->assertEquals(
            ['field_1', '=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_below()
    {
        $filter = new LogicalFilter(
            ['field_1', '<', 2]
        );

        $this->assertEquals(
            ['field_1', '<', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_above()
    {
        $filter = new LogicalFilter(
            ['field_1', '>', 2]
        );

        $this->assertEquals(
            ['field_1', '>', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_and()
    {
        $filter = new LogicalFilter(
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_or()
    {
        $filter = new LogicalFilter(
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ]
        );

        $this->assertEquals(
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not()
    {
        $filter = new LogicalFilter(
            ['not',
                ['field', '>', 3],
            ]
        );

        $this->assertEquals(
            ['not',
                ['field', '>', 3],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_in()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']]
        );

        $this->assertEquals(
            ['field_1', 'in', ['a', 'b', 'c']],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_above_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_below_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not_in()
    {
        $filter = new LogicalFilter(
            ['field_1', '!in', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '!in', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between()
    {
        $filter = new LogicalFilter(
            ['field_1', '><', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '><', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_lower()
    {
        $filter = new LogicalFilter(
            ['field_1', '=><', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '=><', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_upper()
    {
        $filter = new LogicalFilter(
            ['field_1', '><=', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '><=', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_both()
    {
        $filter = new LogicalFilter(
            ['field_1', '=><=', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '=><=', [2, 3]],
            $filter->toArray()
        );
    }

// TEST BELOW ARE NOT STRUCTURED

    /**
     */
    public function test_construct()
    {
        $filter = new LogicalFilter(['field', 'above', 3]);

        $this->assertEquals(
            (new AboveRule('field', 3))->toArray(),
            $filter->getRules()->toArray()
        );

        $filter = new LogicalFilter(new AboveRule('field', 3));

        $this->assertEquals(
            (new AboveRule('field', 3))->toArray(),
            $filter->getRules()->toArray()
        );
    }

    /**
     */
    public function test_addRule_after_invalid_in()
    {
        $filter = new LogicalFilter;

        $filter
            ->and_('field', 'in', [])
            ->and_('field2', '=', 'dfghjkl')
            ;

        $this->assertEquals(
            ['and',
                ['field', 'in', []],
                ['field2', '=', 'dfghjkl'],
            ],
            $filter
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_addRule_with_bad_operation()
    {
        $filter = new LogicalFilter( ['field_1', '=', 3] );

        try {
            VisibilityViolator::callHiddenMethod(
                $filter, 'addRule', [new EqualRule('field', 2), 'bad_operator']
            );

            $this->assertTrue(
                false,
                "An exception claiming that an invaid operator is given "
                ."should have been thrown here"
            );
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true, "InvalidArgumentException throw: ".$e->getMessage());
        }
    }

    /**
     * @todo this looks that a useless uncleaned beginning of test
     */
    public function test_addRules_requiring_strict_check_of_operators()
    {
        $this->assertEquals(
            ['not', ['depth', '=', 0]],
            (new LogicalFilter(['not', ['depth', '=', 0]]))
            // ->dump(true)
            ->toArray()
        );
    }

    /**
     */
    public function test_or__of_true_filter()
    {
        // A || True <=> True
        $filter = (new LogicalFilter(
            ['field_2', '!=', 4]
        ))
        ->or_( (new LogicalFilter)->getRules() )
        // ->dump(true)
        ;

        $this->assertEquals(
            null, // TODO replace it by TrueRule
            $filter->getRules()
        );
    }

    /**
     */
    public function test_and__of_true_filter()
    {
        // A && True <=> A
        $filter = (new LogicalFilter(
            ['field_2', '!=', 4]
        ))
        ->and_( (new LogicalFilter)->getRules() )
        // ->dump(true)
        ;

        $this->assertEquals(
            ['field_2', '!=', 4],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_construct_with_null_description()
    {
        // null <=> no rule <=> true
        $filter = (new LogicalFilter(null))
            // ->dump(true)
            ;

        $this->assertNull( $filter->getRules() );

        $filter = (new LogicalFilter())
            ->and_(null)
            // ->dump(true)
            ;

        $this->assertNull( $filter->getRules() );

        $filter = (new LogicalFilter())
            ->or_(null)
            // ->dump(true)
            ;

        $this->assertNull( $filter->getRules() );

        $filter = (new LogicalFilter())
            ->and_(new LogicalFilter)
            // ->dump(true)
            ;

        $this->assertNull( $filter->getRules() );

        $filter = (new LogicalFilter())
            ->or_(new LogicalFilter)
            // ->dump(true)
            ;

        $this->assertNull( $filter->getRules() );
    }

    /**
     */
    public function test_construct_with_true_false_null_descriptions()
    {
        $filter = (new LogicalFilter(
                ['and',
                    ['field', '=', 'azerty'],
                    true,
                ]
            ))
            // ->dump(true)
            ;

        $this->assertEquals(
            ['and',
                ['field', '=', 'azerty'],
            ],
            $filter
                // ->dump()
                ->toArray()
        );

        // null <=> no rule <=> true
        $filter = (new LogicalFilter(
                ['and',
                    ['field', '=', 'azerty'],
                    null,
                ]
            ))
            // ->dump(true)
            ;

        $this->assertEquals(
            ['and',
                ['field', '=', 'azerty'],
            ],
            $filter
                // ->dump()
                ->toArray()
        );

        // false
        $filter = (new LogicalFilter(
                ['and',
                    ['field', '=', 'azerty'],
                    false,
                ]
            ))
            // ->dump(true)
            ;

        $this->assertEquals(
            ['and',
                ['field', '=', 'azerty'],
                ['and'],  // FalseRule hack
            ],
            $filter
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_addRules_with_nested_operations()
    {
        $filter = new LogicalFilter();
        $filter->and_([
            ['field', 'in', ['a', 'b', 'c']],
            'or',
            [
                ['field', 'in', ['d', 'e']],
                'and',
                [
                    ['field_2', 'above', 3],
                    'or',
                    ['field_3', 'below', -2],
                ],
            ],
        ]);

        $this->assertEquals(
            (new OrRule([
                new InRule('field', ['a', 'b', 'c']),
                new AndRule([
                    new InRule('field', ['d', 'e']),
                    new OrRule([
                        new AboveRule('field_2', 3),
                        new BelowRule('field_3', -2),
                    ]),
                ]),
            ]))->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_addRules_without_operator()
    {
        $filter = new LogicalFilter();

        // exception if no operator in an operation
        try {
            $filter->and_([
                ['field_2', 'above', 3],
                ['field_3', 'below', -2],
                ['field_3', 'equal', 0],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown while operator is missing in an operation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Please provide an operator for the operation: /",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     */
    public function test_addRules_with_different_operators()
    {
        $filter = new LogicalFilter();

        // exception if different operators in the same operation
        try {
            $filter->and_([
                ['field', 'in', ['a', 'b', 'c']],
                'or',
                [
                    ['field', 'in', ['d', 'e']],
                    'and',
                    [
                        ['field_2', 'above', 3],
                        'or',
                        ['field_3', 'below', -2],
                        'and',
                        ['field_3', 'equal', 0],
                    ],
                ],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown for different operators in one operation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Mixing different operations in the same rule level not implemented:/",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     */
    public function test_addRules_with_negation()
    {
        $filter = new LogicalFilter();

        $filter->and_([
            'not',
            ['field_2', 'above', 3],
        ]);

        $this->assertEquals(
            (new NotRule(
                new AboveRule('field_2', 3)
            ))->toArray(),
            $filter->getRules()->toArray()
        );

        // not with too much operands
        try {
            $filter->and_([
                'not',
                ['field_2', 'above', 3],
                ['field_2', 'equal', 5],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown if two operands for a negation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Negations can have only one operand: /",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     */
    public function test_addRules_on_noSolution_filter()
    {
        // and root
        $filter = (new LogicalFilter([
            'and'
        ]))
        ;

        try {
            $filter->and_('a', '<', 'b');
            $this->assertFalse(false, "Adding rule to an invalid filter not forbidden");
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
            $e->getMessage() ==  "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have been simplified.";
        }

        // or root
        $filter = (new LogicalFilter([
            'or',
        ]))
        ;

        try {
            $filter->and_('a', '<', 'b');
            $this->assertFalse(false, "Adding rule to an invalid filter not forbidden");
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
            $e->getMessage() ==  "You are trying to add rules to a LogicalFilter which had "
                ."only contradictory rules that have been simplified.";
        }
    }

    /**
     */
    public function test_addRules_with_symbolic_operators()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            ['field_5', '<', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]);

        $this->assertEquals(
            [
                'and',
                ['field_5', '>', 'a'],
                ['field_5', '<', 'a'],
                [
                    'not',
                    ['field_5', '=', 'a'],
                ],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_regexp()
    {
        $filter = new LogicalFilter(
            ['field_1', 'regexp', '/^lalala*/']
        );

        $this->assertEquals(
            ['field_1', 'regexp', '/^lalala*/'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_addRules_from_toArray()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            ['field_5', '<', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]);

        $this->assertEquals(
            $filter->toArray(),
            (new LogicalFilter( $filter->toArray() ))->toArray()
        );
    }

    /**
     */
    public function test_LogicalFilter_or_AbstractRule_in_operations_descriptions()
    {
        $filter_to_use = (new LogicalFilter(
            ['field_1', '=', 'azerty']
        ));
        $filter_to_use2 = (new LogicalFilter(
            ['field_2', '=', 'lalala']
        ));

        // not
        $negated_filter = (new LogicalFilter(
            ['not', $filter_to_use]
        ));

        $this->assertEquals(
            ['not',
                ['field_1', '=', 'azerty'],
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );

        $negated_filter = (new LogicalFilter(
            ['not', $filter_to_use->getRules()]
        ));

        $this->assertEquals(
            ['not',
                ['field_1', '=', 'azerty'],
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );

        // or
        $negated_filter = (new LogicalFilter(
            ['or', $filter_to_use, $filter_to_use2]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '=', 'azerty'],
                ['field_2', '=', 'lalala']
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );

        $negated_filter = (new LogicalFilter(
            ['or', $filter_to_use->getRules(), $filter_to_use2->getRules()]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '=', 'azerty'],
                ['field_2', '=', 'lalala']
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );

        // and
        $negated_filter = (new LogicalFilter(
            ['and', $filter_to_use, $filter_to_use2]
        ));

        $this->assertEquals(
            ['and',
                ['field_1', '=', 'azerty'],
                ['field_2', '=', 'lalala']
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );

        $negated_filter = (new LogicalFilter(
            ['and', $filter_to_use->getRules(), $filter_to_use2->getRules()]
        ));

        $this->assertEquals(
            ['and',
                ['field_1', '=', 'azerty'],
                ['field_2', '=', 'lalala']
            ],
            $negated_filter
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_and_simple()
    {
        $filter = new LogicalFilter();

        $filter->and_('field', 'in', ['a', 'b', 'c']);
        $filter->and_('field', 'above', 3);
        $filter->and_('field', 'below', 5);

        $this->assertEquals(
            (new AndRule([
                new InRule('field', ['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]))->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_or_simple()
    {
        $filter = new LogicalFilter();

        $filter->or_('field', 'in', ['a', 'b', 'c']);
        $filter->or_('field', 'above', 3);
        $filter->or_('field', 'below', 5);

        $this->assertEquals(
            (new OrRule([
                new InRule('field', ['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]))
                ->toArray(),
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_and_with_rules_instances_in_array()
    {
        // in an array
        $filter = new LogicalFilter();

        $filter->and_([
            new InRule('field', ['a', 'b', 'c']),
            new AboveRule('field', 3),
            new BelowRule('field', 5)
        ])
        // ->dump()
        ;

        $this->assertEquals(
            (new AndRule([
                new InRule('field', ['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]))
                ->toArray(),
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_or_with_rules_instances()
    {
        $filter = new LogicalFilter();

        $filter
            ->or_(new InRule('field', ['a', 'b', 'c']))
            ->or_(new AboveRule('field', 3))
            ->or_(new BelowRule('field', 5))
            ;

        $this->assertEquals(
            (new OrRule([
                new InRule('field', ['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]))
                ->toArray(),
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_or_with_rules_instances_in_array()
    {
        // in an array
        $filter = new LogicalFilter();

        $filter->or_([
            new InRule('field', ['a', 'b', 'c']),
            new AboveRule('field', 3),
            new BelowRule('field', 5)
        ])
        ;

        $this->assertEquals(
            (new OrRule([
                new InRule('field', ['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]))
                ->toArray(),
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_AboveRule_with_non_scalar()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_1', '>', null],
            ['field_2', '>', 'a'],
            ['field_5', '>', 3],
            ['field_5', '>', new \DateTime('2018-06-11')],
            ['field_5', '>', new \DateTimeImmutable('2018-06-11')],
        ]));

        try {
            $filter = (new LogicalFilter(
                ['field_1', '>', [12, 45]]
            ));
            $this->assertTrue(false, "An exception should be throw here");
        }
        catch (\InvalidArgumentException $e) {
            // InvalidArgumentException: Minimum parameter must be a scalar
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_BelowRule_with_non_scalar()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_1', '<', null],
            ['field_2', '<', 'a'],
            ['field_5', '<', 3],
            ['field_5', '<', new \DateTime('2018-06-11')],
            ['field_5', '<', new \DateTimeImmutable('2018-06-11')],
        ]));

        try {
            $filter = (new LogicalFilter(
                ['field_1', '<', ['lalala', 2]]
            ));
            $this->assertTrue(false, "An exception should be throw here");
        }
        catch (\InvalidArgumentException $e) {
            // InvalidArgumentException: Maximum parameter must be a scalar
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_and_of_LogicalFilter()
    {
        $filter  = new LogicalFilter( ['field_1', '=', 3] );
        $filter2 = new LogicalFilter( ['field_2', '=', 12] );

        $this->assertEquals(
            [
                'and',
                ['field_1', '=', 3],
                ['field_2', '=', 12],
            ],
            $filter
                ->and_( $filter2 )
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_and_of_AbstractRules()
    {
        $filter = new LogicalFilter( ['field_1', '=', 3] );
        $rule1  = new EqualRule( 'field_2', 12 );
        $rule2  = new AboveRule( 'field_3', 'abc' );

        $this->assertEquals(
            [
                'and',
                ['field_1', '=', 3],
                ['field_2', '=', 12],
                ['field_3', '>', 'abc'],
            ],
            $filter
                ->and_( $rule1, $rule2 )
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_and_of_invalid_rules_description_throws_exception()
    {
        $filter = new LogicalFilter( ['field_1', '=', 3] );

        try {
            $filter->and_('a', '=', '3', 'lalalalala');
            $this->assertTrue(
                false,
                "An exception claiming that bad arguments are provided "
                ."should have been thrown here"
            );
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true, "InvalidArgumentException throw: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_and_of_invalid_rules_description_containing_unhandled_operation()
    {
        try {
            $filter = new LogicalFilter( ['operator_of_unhandled_operation', ['filed_1', '=', 3]] );
            $this->assertTrue(
                false,
                "An exception claiming that an unhandled operation is described "
                ."into a rules description should have been thrown here"
            );
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true, "InvalidArgumentException throw: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_action_on_value()
    {
        $filter = (new LogicalFilter(
            [
                [value()['col_1'], '=', 'lololo'],
                'or',
                [key(), '=', 'key_1'],
            ]
        ))
        // ->dump(true)
        ;

        $array = [
            'key_0' => [
                'col_1' => 'lelele',
                'col_2' => 'lylyly',
            ],
            'key_1' => [
                'col_1' => 'lalala',
                'col_2' => 'lilili',
            ],
            'key_2' => [
                'col_1' => 'lololo',
                'col_2' => 'lululu',
            ],
        ];

        $this->assertEquals(
            [
                'key_1' => [
                    'col_1' => 'lalala',
                    'col_2' => 'lilili',
                ],
                'key_2' => [
                    'col_1' => 'lololo',
                    'col_2' => 'lululu',
                ],
            ],
            $filter
                ->applyOn($array)
        );
    }

    /**
     */
    public function test_addRules_with_operands_indexed_by_semantic_ids()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '=', 3],
                ['field_5', '>', 3],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            '2c56a5c6c510a7951a95909bbe59176c-511711247b38d5ed3ee96dee4d3bf89a',
            $filter->getRules()->getSemanticId()
        );

        $filter2 = (new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['field_6', '>', 4],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            '53c79fcddef526b3293af07c9631d1f4-511711247b38d5ed3ee96dee4d3bf89a',
            $filter2->getRules()->getSemanticId()
        );

        $filter3 = (new LogicalFilter(
            ['and',
                ['or',
                    ['and',
                        ['field_1', '=', 3],
                        ['field_5', '>', 3],
                    ],
                    ['and',
                        ['field_1', '=', 2],
                        ['field_6', '>', 4],
                    ],
                ],
                ['field_7', '=', 2],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ['and',
                ['or',
                    ['and',
                        ['field_1', '=', 3],
                        ['field_5', '>', 3],
                    ],
                    ['and',
                        ['field_1', '=', 2],
                        ['field_6', '>', 4],
                    ],
                ],
                ['field_7', '=', 2],
            ],
            $filter3
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_getSemanticId_with_same_operands_in_different_order()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_1', '=', 3],
                    ['field_5', '>', 3],
                ],
                ['and',
                    ['field_5', '>', 3],
                    ['field_1', '=', 3],
                ],
                ['field_2', '!=', 4],
            ]
        ))
        // ->dump(true)
        ;

        $filter2 = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_5', '>', 3],
                    ['field_1', '=', 3],
                ],
                ['field_2', '!=', 4],
                ['and',
                    ['field_1', '=', 3],
                    ['field_5', '>', 3],
                ],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            $filter->getRules()->getSemanticId(),
            $filter2->getRules()->getSemanticId()
        );
    }

    /**/
}
