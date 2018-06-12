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


class LogicalFilterTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
    }

    /**
     */
    public function test_construct()
    {
        $filter = new LogicalFilter(['field', 'above', 3]);

        $this->assertEquals(
            new AboveRule('field', 3),
            $filter->getRules()
        );
    }

    /**
     */
    public function test_and_simple()
    {
        $filter = new LogicalFilter();

        $filter->and_('field', 'in', ['a', 'b', 'c']);
        // $filter->addRule('field', 'not_in', ['a', 'b', 'c']);
        $filter->and_('field', 'above', 3);
        $filter->and_('field', 'below', 5);

        $rules = VisibilityViolator::getHiddenProperty(
            $filter,
            'rules'
        );

        $this->assertEquals(
            new AndRule([
                new InRule('field', ['a', 'b', 'c']),
                // new NotInRule(['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]),
            $rules
        );
    }

    /**
     */
    public function test_getRules()
    {
        $filter = new LogicalFilter();
        $filter->and_('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            new InRule('field', ['a', 'b', 'c']),
            $filter->getRules()
        );
    }

    /**
     */
    public function test_addOrRule()
    {
        $filter = new LogicalFilter();

        $filter->and_([
            ['field', 'in', ['a', 'b', 'c']],
            'or',
            ['field', 'equal', 'e']
        ]);

        $this->assertEquals(
            (new OrRule([
                new InRule('field', ['a', 'b', 'c']),
                new EqualRule('field', 'e')
            ]))->toArray(),
            $filter->getRules()->toArray()
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
    public function test_simplify_removeNegations_basic()
    {
        $filter = new LogicalFilter();

        $filter->and_([
            'not',
            ['field_2', 'above', 3],
        ]);

        $filter->simplify();

        $this->assertEquals(
            (new OrRule([
                new BelowRule('field_2', 3),
                new EqualRule('field_2', 3),
            ]))
            ->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_removeNegations_complex()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_1', 'below', 3],
            ['not', ['field_2', 'above', 3]],
            ['not', ['field_3', 'in', [7, 11, 13]]],
            ['not',
                [
                    'or',
                    ['field_4', 'below', 2],
                    ['field_5', 'in', ['a', 'b', 'c']],
                ],
            ],
        ]))
        ->simplify(['stop_after' => AbstractOperationRule::remove_negations])
        ;

        $filter2 = new LogicalFilter([
            'and',
            [
                'or',
                ['field_1', 'below', 3],
                // ['not', ['field_2', 'above', 3]],
                [
                    'or',
                    ['field_2', 'below', 3],
                    ['field_2', 'equal', 3],
                ],
                // ['not', ['field_3', 'in', [7, 11, 13]]],
                [
                    'and',
                    [
                        'or',
                        ['field_3', 'above', 7],
                        ['field_3', 'below', 7],
                    ],
                    [
                        'or',
                        ['field_3', 'above', 11],
                        ['field_3', 'below', 11],
                    ],
                    [
                        'or',
                        ['field_3', 'above', 13],
                        ['field_3', 'below', 13],
                    ],
                ],
                // ['not',
                    // [
                        // 'or',
                        // ['field_4', 'below', 2],
                        // ['field_5', 'in', ['a', 'b', 'c']],
                    // ],
                // ],
                [
                    'and',
                    [
                        'or',
                        ['field_4', 'above', 2],
                        ['field_4', 'equal', 2],
                    ],
                    [
                        'and',
                        [
                            'or',
                            ['field_5', 'above', 'a'],
                            ['field_5', 'below', 'a'],
                        ],
                        [
                            'or',
                            ['field_5', 'above', 'b'],
                            ['field_5', 'below', 'b'],
                        ],
                        [
                            'or',
                            ['field_5', 'above', 'c'],
                            ['field_5', 'below', 'c'],
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunctions_minimal()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_5', 'above', 'a'],
            ['field_5', 'below', 'a'],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump()
        ;

        $filter2 = (new LogicalFilter([
            'or',
            [
                'and',
                ['field_5', 'above', 'a'],
            ],
            [
                'and',
                ['field_5', 'below', 'a'],
            ],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump()
        ;

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunctions_basic()
    {
        $filter = (new LogicalFilter([
            'and',
            [
                'or',
                ['field_4', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ],
            ['field_6', 'equal', 'b'],
        ]))
        ->simplify()
        // ->dump()
        ;

        $filter2 = (new LogicalFilter([
            'or',
            [
                'and',
                ['field_4', 'above', 'a'],
                ['field_6', 'equal', 'b'],
            ],
            [
                'and',
                ['field_5', 'below', 'a'],
                ['field_6', 'equal', 'b'],
            ],
        ]))
        // ->dump(true)
        ;

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunctions_complex()
    {
        $filter = new LogicalFilter();

        // (A' || A") && (B' || B") && (C' || C") <=>
        //    (A' && B' && C') || (A' && B' && C") || (A' && B" && C') || (A' && B" && C")
        // || (A" && B' && C') || (A" && B' && C") || (A" && B" && C') || (A" && B" && C");
        $filter->and_([
            'and',
            [
                'or',
                ['field_51', 'above', '5'],
                ['field_52', 'below', '5'],
            ],
            [
                'or',
                ['field_61', 'above', '6'],
                ['field_62', 'below', '6'],
            ],
            [
                'or',
                ['field_71', 'above', '7'],
                ['field_72', 'below', '7'],
            ],
        ]);

        $filter->simplify();

        $filter2 = new LogicalFilter;
        $filter2->and_([
            'or',
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_61', 'above', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_61', 'above', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_62', 'below', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_62', 'below', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_61', 'above', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_61', 'above', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_62', 'below', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_62', 'below', '6'],
                ['field_72', 'below', '7'],
            ],
        ]);

        $this->assertEquals(
            $filter->toArray(),
            $filter2->toArray()
        );
    }

    /**
     */
    public function test_hasSolution()
    {
        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'equal', 'a'],
                ['field_5', 'below', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'equal', 'a'],
                ['field_5', 'above', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertTrue(
            (new LogicalFilter([
                'or',
                [
                    'and',
                    ['field_5', 'above', 'a'],
                    ['field_5', 'below', 'a'],
                ],
                ['field_6', 'equal', 'b'],
            ]))
            ->hasSolution()
        );
    }

    /**
     */
    public function test_hasSolution_on_null_filter()
    {
        // A filter has all solutions if it contains no rule.
        $filter = new LogicalFilter;
        $this->assertTrue( $filter->hasSolution() );
    }

    /**
     */
    public function test_hasSolution_saving_simplification()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 'a'],
            ['field_1', '=', 'b'],
        ]);

        // don't save simplifications
        $this->assertFalse( $filter->hasSolution(false) );
        $this->assertEquals([
            'and',
            ['field_1', '=', 'a'],
            ['field_1', '=', 'b'],
        ], $filter->toArray() );

        // saving simplifications
        $this->assertFalse( $filter->hasSolution() );
        $this->assertEquals( ['and'], $filter->toArray() );
    }

    /**
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function test_jsonSerialize()
    {
        $this->assertEquals(
            '["or",["and",["field_5",">","a"],["field_5","<","a"]],["field_6","=","b"]]',
            json_encode(
                new LogicalFilter([
                    'or',
                    [
                        'and',
                        ['field_5', 'above', 'a'],
                        ['field_5', 'below', 'a'],
                    ],
                    ['field_6', 'equal', 'b'],
                ])
            )
        );
    }

    /**
     */
    public function test_copy()
    {
        $filter = new LogicalFilter([
            'or',
            [
                'and',
                ['field_5', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ],
            ['field_6', 'equal', 'b'],
        ]);

        $filter2 = $filter->copy();

        $this->assertEquals($filter, $filter2);

        $this->assertNotEquals(
            spl_object_hash($filter->getRules(false)),
            spl_object_hash($filter2->getRules(false))
        );
    }

    /**
     */
    public function test_addRules_on_noSolution_filter()
    {
        $filter = (new LogicalFilter([
            'and',
            [
                'or',
                ['field_5', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ],
            ['field_5', 'equal', 'a'],
        ]))
        ->simplify()
        ;

        $this->assertEmpty(
            $filter
                ->getRules()
                // ->dump()
                ->getOperands()
        );

        try {
            $filter->and_('a', '<', 'b');
            $this->assertFalse(false, "Adding rule to an invalid filter not forbidden");
        }
        catch (\Exception $e) {
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
    public function test_simplify_basic()
    {
        // $filter = (new LogicalFilter([
            // 'and',
            // ['field_5', '>', 'a'],
            // ['field_6', '<', 'a'],
            // [
                // '!',
                // ['field_5', '=', 'a'],
            // ],
        // ]);

        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            ['field_5', '>', 5],
        ]))
        ->simplify()
        ;

        $this->assertTrue( $filter->getRules()->isSimplified() );

        $this->assertEquals( ['field_5', '>', 5], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_nested_and_operations()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            [
                'and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
        ]))
        ->simplify( ['stop_before' => AbstractOperationRule::rootify_disjunctions] )
        ;

        $this->assertEquals( [
                'and',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            $filter->toArray()
        );

        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            [
                'and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                [
                    'and',
                    ['field_8', '>', 3],
                    ['field_9', '>', 5],
                ],
            ],
        ]))
        ->simplify( ['stop_before' => AbstractOperationRule::rootify_disjunctions] )
        ;

        $this->assertEquals( [
                'and',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                ['field_8', '>', 3],
                ['field_9', '>', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_nested_or_operations()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_5', '>', 3],
            [
                'or',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
        ]))
        ->simplify()
        // ->dump(!true, false)
        ;

        $this->assertEquals( [
                'or',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            $filter->toArray()
        );


        $filter = (new LogicalFilter([
            'or',
            ['field_5', '>', 3],
            [
                'or',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                [
                    'or',
                    ['field_8', '>', 3],
                    ['field_9', '>', 5],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(!true, false)
        ;

        $this->assertEquals( [
                'or',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                ['field_8', '>', 3],
                ['field_9', '>', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_multiple_equals()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '=', 3],
            ['field_5', '=', 5],
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEmpty(
            $filter->getRules()->getOperands()
        );
    }

    /**
     */
    public function test_simplify_multiple_aboves()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            ['field_5', '>', 5],
            ['field_5', '>', 5],
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '>', 5],
            $filter->getRules()->toArray()
        );
    }

    /**
     */
    public function test_simplify_multiple_below()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '<', 3],
            ['field_5', '<', 5],
            ['field_5', '<', 5],
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '<', 3],
            $filter->getRules()->toArray()
        );
    }

    /**
     */
    public function test_simplify_unifyOperands_inRecursion()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump(false, false)
        ;

        $this->assertEquals( [
            'or',
            [
                'and',
                ['field_5', '>', 'a'],
            ],
        ], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_with_negation_without_logicalCore()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '>', 'a'], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_remove_monooperands_and()
    {
        $filter = (new LogicalFilter([
            'and',
            [
                'and',
                [
                    'and',
                    ['field_5', '=', 'a'],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '=', 'a'], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_remove_monooperands_or()
    {
        $filter = (new LogicalFilter([
            'or',
            [
                'or',
                [
                    'or',
                    ['field_5', '=', 'a'],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '=', 'a'], $filter->toArray() );
    }

    /**
     */
    public function test_add_InRule()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', 'in', ['a', 'b', 'c']],
            $filter->toArray()
        );

        $filter->getRules(false)->addPossibilities(['d', 'e']);

        $this->assertEquals(
            ['a', 'b', 'c', 'd', 'e'],
            $filter->getRules()->getPossibilities()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '=', 'a'],
                ['field_1', '=', 'b'],
                ['field_1', '=', 'c'],
                ['field_1', '=', 'd'],
                ['field_1', '=', 'e'],
            ],
            $filter->simplify()->toArray()
        );
    }

    /**
     */
    public function test_add_NotEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter->toArray()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '>', 'a'],
                ['field_1', '<', 'a'],
            ],
            $filter->simplify()->toArray()
        );
    }

    /**
     */
    public function test_add_AboveOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter->toArray()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '>', 2],
                ['field_1', '=', 2],
            ],
            $filter->simplify()->toArray()
        );
    }

    /**
     */
    public function test_add_BelowOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->toArray()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '<', 2],
                ['field_1', '=', 2],
            ],
            $filter->simplify()->toArray()
        );
    }

    /**
     */
    public function test_add_NotInRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!in', [2, 3]]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!in', [2, 3]],
            $filter->toArray()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '>', 3],
                [
                    'and',
                    ['field_1', '>', 2],
                    ['field_1', '<', 3],
                ],
                ['field_1', '<', 2],
            ],
            $filter->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_AboveRule_with_null_as_value()
    {
        try {
            $filter = (new LogicalFilter(
                ['field_1', '>', null]
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
    public function test_BelowRule_with_null_as_value()
    {
        try {
            $filter = (new LogicalFilter(
                ['field_1', '<', null]
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
    public function test_NotRule_of_long_and()
    {
        $filter = (new LogicalFilter(
            ['not',
                ['and',
                    ['field_1', '=', 2],
                    ['field_2', '<', 3],
                    ['field_3', '>', 4],
                ],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ['or',
                ['and', // 1
                    ['not', ['field_1', '=', 2]],
                    ['field_2', '<', 3],
                    ['field_3', '>',  4],
                ],

                ['and', // 2
                    ['field_1', '=', 2],
                    ['not', ['field_2', '<', 3]],
                    ['field_3', '>',  4],
                ],

                ['and', // 3
                    ['not', ['field_1', '=', 2]],
                    ['not', ['field_2', '<', 3]],
                    ['field_3', '>',  4],
                ],

                ['and', // 4
                    ['field_1', '=', 2],
                    ['field_2', '<', 3],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 5
                    ['not', ['field_1', '=', 2]],
                    ['field_2', '<', 3],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 6
                    ['field_1', '=', 2],
                    ['not', ['field_2', '<', 3]],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 7
                    ['not', ['field_1', '=', 2]],
                    ['not', ['field_2', '<', 3]],
                    ['not', ['field_3', '>',  4]],
                ],
            ],
            $filter
                ->getRules()
                ->negateOperand()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_NotRule_of_null()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ));

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter->toArray()
        );

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter
                ->simplify()
                // ->dump(!true)
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '!=', null],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump(!true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['not', ['field_1', '=', null]]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            ['not', ['field_1', '=', null]],
            $filter
                ->toArray()
        );

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_above_or_below()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '>', 3])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_NotEqualRule_of_null_and_above_or_below()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['field_1', '<', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '>', 3])
        ;

        $this->assertEquals(
            ['field_1', '>', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        // within OrRule
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->or_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['or',
                ['field_1', '!=', null],
                ['field_1', '<', 3],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->or_(['field_1', '>', 3])
        ;

        $this->assertEquals(
            ['or',
                ['field_1', '!=', null],
                ['field_1', '>', 3],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_NotEqualRule_of_null_and_equal()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '=', 3])
        ;

        $this->assertEquals(
            ['field_1', '=', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_NotEqualRule_of_null_giving_false()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '!=', null])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                // ->dump(true)
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_NotEqualRule_of_null_giving_true()
    {
        $this->markTestSkipped('Requires the support operands being TRUE or FALSE');
        // TODO this filter should just value true as it doesn't filter
        // anything. @see https://github.com/jclaveau/php-logical-filter/issues/38
        // to replace the OrRule by a TrueRule
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->or_(['field_1', '!=', null])
        ;

        $this->assertEquals(
            [
                'or',
                true,
                // ['field_1', '=', null],
                // ['field_1', '!=', null],
            ],
            $filter
                // ->dump(true)
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_equal()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '=', 3])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_add_BetweenRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '><', [2, 3]]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '><', [2, 3]],
            $filter->toArray()
        );

        $this->assertEquals(
            [
                'and',
                ['field_1', '>', 2],
                ['field_1', '<', 3],
            ],
            $filter->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_BelowRule_and_AboveRule_are_strictloy_compared()
    {
        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_1', '=', 3],
                ['field_1', '<', 3],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_1', '=', 3],
                ['field_1', '>', 3],
            ]))
            ->hasSolution()
        );
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
     */
    public function test_forceLogicalCore_with_AtomicRule_at_root()
    {
        $filter = new LogicalFilter( ['field_1', '=', 3] );

        $this->assertEquals(
            [
                'or',
                [
                    'and',
                    ['field_1', '=', 3],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_AndRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->and_(['field_2', '=', 4])
            // ->dump()
            ;

        $this->assertEquals(
            [
                'or',
                [
                    'and',
                    ['field_1', '=', 3],
                    ['field_2', '=', 4],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_OrRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->or_(['field_2', '=', 4])
            // ->dump()
            ;

        $this->assertEquals(
            [
                'or',
                [
                    'and',
                    ['field_1', '=', 3],
                ],
                [
                    'and',
                    ['field_2', '=', 4],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_NotRule_at_root()
    {
        $filter = (new LogicalFilter( ['not', ['field_1', '=', 3]] ))
            // ->dump()
            ;

        try {
            VisibilityViolator::callHiddenMethod(
                $filter->getRules(), 'forceLogicalCore'
            );

            $this->assertTrue(false, "forceLogicalCore() must throw an exception here");
        }
        catch (\LogicException $e) {
            $this->assertTrue(true, "Exception thrown: " . $e->getMessage());
        }
    }

    /**
     * @todo debug the runInseparateProcess of php to test the exit call.
     * @ runInSeparateProcess
     */
    public function test_dump()
    {
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump()
            ;
        $dump = ob_get_clean();

        // simple chained dump
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
                array (
                  0 => 'field_1',
                  1 => '=',
                  2 => 3,
                )

                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

        // instance debuging enabled
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, true)
            ;
        $dump = ob_get_clean();
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
                array (
                  0 => 'field_1',
                  1 => 'JClaveau\\\\LogicalFilter\\\\Rule\\\\EqualRule:XX',
                  2 => 3,
                )

                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

        // exit once dumped
        // TODO this makes phpunit bug while echoing text before calling exit;
        // ob_start();
        // $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            // ->dump(true)
            // ;
            // echo 'plop';
            // exit;
        // $dump = ob_get_clean();
        // $this->assertEquals(
            // str_replace('    ', '', "
                // /home/jean/dev/mediabong/apps/php-logical-filter/tests/public api/LogicalFilterTest.php:XX
                // array (
                  // 0 => 'field_1',
                  // 1 => '=',
                  // 2 => 3,
                // )

                // "
            // ),
            // preg_replace('/:\d+/', ':XX', $dump)
        // );

    }

    /**
     */
    public function test_applyOn_without_filterer()
    {
        $filtered_data = (new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_4', '!=', null],
        ]))
        ->applyOn([
            [
                'name'    => '1: valid row',
                'field_1' => 2,
                'field_2' => 12,
                // no filed 3 <=> null
                'field_4' => 12,
            ],
            [
                'name'    => '2: field_1 invalid',
                'field_1' => 3,
                'field_2' => 12,
                'field_4' => "aze",
            ],
            [
                'name'    => '3: field_2 invalid',
                'field_1' => 2,
                'field_2' => 2,
                'field_4' => "aze",
            ],
            [
                'name'    => '4: valid row',
                'field_1' => 2,
                'field_2' => -12,
                'field_3' => null,
                'field_4' => 0,
            ],
            [
                'name'    => '5: field_2 invalid',
                'field_1' => 3,
                'field_2' => 2,
                'field_3' => null,
                'field_4' => 'abc',
            ],
            [
                'name'    => '6: field_3 invalid && filed_4 valid',
                'field_1' => 2,
                'field_2' => -12,
                'field_3' => -12,
                'field_4' => 0, // != null
            ],
            [
                'name'    => '7: field_4 invalid',
                'field_1' => 2,
                'field_2' => -12, // invalid
                'field_4' => null,
            ],
        ]);

        $this->assertEquals(
            [
                0 => [
                'name'    => '1: valid row',
                    'field_1' => 2,
                    'field_2' => 12,
                    'field_4' => 12,
                ],
                3 => [
                'name'    => '4: valid row',
                    'field_1' => 2,
                    'field_2' => -12,
                    'field_3' => null,
                    'field_4' => 0,
                ],
            ],
            $filtered_data
        );
    }


    /**/
}
