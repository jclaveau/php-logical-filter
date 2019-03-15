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
    public function validMinimalisticRulesProvider()
    {
        $valid_rule_descriptions = [
            ['field_1', '=', 2],
            ['field_1', '=', null],
            ['field_1', '<', 2],
            ['field_1', '>', 2],
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            ['not',
                ['field', '>', 3],
            ],
            ['field_1', 'in', ['a', 'b', 'c', null]],
            ['field_1', '>=', 2],
            ['field_1', '<=', 2],
            ['field_1', '!=', 'a'],
            ['field_1', '!=', null],
            ['field_1', '!in', [2, 3]],
            ['field_1', '><', [2, 3]],
            ['field_1', '=><', [2, 3]],
            ['field_1', '><=', [2, 3]],
            ['field_1', '=><=', [2, 3]],
            ['field_1', 'regexp', '/^lalala*/'],
        ];

        $out = [];
        foreach ($valid_rule_descriptions as $valid_rule_description) {
            $out[] = [$valid_rule_description];
        }

        return $out;
    }

    /**
     * @dataProvider validMinimalisticRulesProvider
     */
    public function test_add_valid_rule_description_via_constructor($rule_description)
    {
        $filter = new LogicalFilter(
            $rule_description
        );

        $this->assertEquals(
            $rule_description,
            $filter->toArray()
        );
    }

    /**
     * @dataProvider validMinimalisticRulesProvider
     */
    public function test_add_valid_rule_description_via_and_($rule_description)
    {
        $filter = new LogicalFilter;
        $filter->and_($rule_description);

        $this->assertEquals(
            $rule_description,
            $filter->toArray()
        );
    }

    /**
     * @dataProvider validMinimalisticRulesProvider
     */
    public function test_add_rules_from_toArray($rule_description)
    {
        $filter = new LogicalFilter($rule_description);

        $this->assertEquals(
            $filter->toArray(),
            (new LogicalFilter( $filter->toArray() ))->toArray()
        );
    }

    /**
     */
    public function test_construct()
    {
        $filter = new LogicalFilter(['field', 'above', 3]);

        $this->assertEquals(
            ['field', '>', 3],
            $filter->toArray()
        );

        $filter = new LogicalFilter(new AboveRule('field', 3));

        $this->assertEquals(
            ['field', '>', 3],
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
     * @dataProvider validMinimalisticRulesProvider
     */
    public function test_or__of_true_filter($rule_description)
    {
        // A || True <=> True
        $filter = (new LogicalFilter(
            $rule_description
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
     * @dataProvider validMinimalisticRulesProvider
     */
    public function test_and__of_true_filter($rule_description)
    {
        // A && True <=> A
        $filter = (new LogicalFilter(
            $rule_description
        ))
        ->and_( (new LogicalFilter)->getRules() )
        // ->dump(true)
        ;

        $this->assertEquals(
            $rule_description,
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_descriptions_containing_true_false_null_rules()
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
    public function test_add_rule_after_in_with_no_possibility()
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
    public function test_add_operations_nested()
    {
        $filter = new LogicalFilter();
        $filter->and_(
            ['or',
                ['field', 'in', ['a', 'b', 'c']],
                ['and',
                    ['field', 'in', ['d', 'e']],
                    ['or',
                        ['field_2', '>', 3],
                        ['field_3', '<', -2],
                    ],
                ],
            ]
        );

        $this->assertEquals(
            ['or',
                ['field', 'in', ['a', 'b', 'c']],
                ['and',
                    ['field', 'in', ['d', 'e']],
                    ['or',
                        ['field_2', '>', 3],
                        ['field_3', '<', -2],
                    ],
                ],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_operation_without_operator()
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
    public function test_add_operation_with_different_operators()
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
    public function test_add_negation_with_more_than_one_operand()
    {
        $filter = new LogicalFilter();

        // not with too much operands
        try {
            $filter->and_(
                ['not',
                    ['field_2', 'above', 3],
                    ['field_2', 'equal', 5],
                ]
            );

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
    public function test_add_rules_to_filter_with_no_solution_anymore()
    {
        // and root
        $filter = (new LogicalFilter(
            ['and']
        ));

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
        $filter = (new LogicalFilter(
            ['or']
        ));

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
    public function test_add_negation_with_symbolic_operators()
    {
        $filter = new LogicalFilter(
            ['and',
                ['field_5', '>', 'a'],
                ['field_5', '<', 'a'],
                [
                    '!',
                    ['field_5', '=', 'a'],
                ],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_5', '>', 'a'],
                ['field_5', '<', 'a'],
                ['not',
                    ['field_5', '=', 'a'],
                ],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_operations_containing_LogicalFilter_or_AbstractRule_as_operands()
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
    public function test_or__simple()
    {
        $filter = new LogicalFilter();

        $filter->or_('field', 'in', ['a', 'b', 'c']);
        $filter->or_('field', 'above', 3);
        $filter->or_('field', 'below', 5);

        $this->assertEquals(
            ['or',
                ['field', 'in', ['a', 'b', 'c']],
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_and__with_rules_instances_in_array()
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
            ['and',
                ['field', 'in', ['a', 'b', 'c']],
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter
                ->toArray()
        );
    }

    /**
     */
    public function test_or__with_rules_instances()
    {
        $filter = new LogicalFilter();

        $filter
            ->or_(new InRule('field', ['a', 'b', 'c']))
            ->or_(new AboveRule('field', 3))
            ->or_(new BelowRule('field', 5))
            ;

        $this->assertEquals(
            ['or',
                ['field', 'in', ['a', 'b', 'c']],
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_or__with_rules_instances_in_array()
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
            ['or',
                ['field', 'in', ['a', 'b', 'c']],
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_add_above_with_non_scalar()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '>', null],
                ['field_2', '>', 'a'],
                ['field_5', '>', 3],
                ['field_5', '>', new \DateTime('2018-06-11')],
                ['field_5', '>', new \DateTimeImmutable('2018-06-11')],
            ]
        ));

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
    public function test_add_below_with_non_scalar()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '<', null],
                ['field_2', '<', 'a'],
                ['field_5', '<', 3],
                ['field_5', '<', new \DateTime('2018-06-11')],
                ['field_5', '<', new \DateTimeImmutable('2018-06-11')],
            ]
        ));

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
    public function test_and__of_LogicalFilter()
    {
        $filter  = new LogicalFilter(['field_1', '=', 3]);
        $filter2 = new LogicalFilter(['field_2', '=', 12]);

        $this->assertEquals(
            ['and',
                ['field_1', '=', 3],
                ['field_2', '=', 12],
            ],
            $filter
                ->and_($filter2)
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_and__of_rule_instances()
    {
        $filter = new LogicalFilter(['field_1', '=', 3]);
        $rule1  = new EqualRule('field_2', 12);
        $rule2  = new AboveRule('field_3', 'abc');

        $this->assertEquals(
            ['and',
                ['field_1', '=', 3],
                ['field_2', '=', 12],
                ['field_3', '>', 'abc'],
            ],
            $filter
                ->and_($rule1, $rule2)
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_and__of_invalid_rules_description_throws_exception()
    {
        $filter = new LogicalFilter(['field_1', '=', 3]);

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
    public function test_and__of_invalid_rules_description_containing_unhandled_operation()
    {
        try {
            $filter = new LogicalFilter(['operator_of_unhandled_operation', ['filed_1', '=', 3]]);
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
    public function test_add_rules_with_operands_indexed_by_semantic_ids()
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
