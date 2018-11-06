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

require  __DIR__ . "/LogicalFilterTest_rules_manipulation_trait.php";
require  __DIR__ . "/LogicalFilterTest_rules_simplification_trait.php";
require  __DIR__ . "/LogicalFilterTest_collection_integration_trait.php";

class LogicalFilterTest extends \AbstractTest
{
    use LogicalFilterTest_rules_manipulation_trait;
    use LogicalFilterTest_rules_simplification_trait;
    use LogicalFilterTest_collection_integration_trait;

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
    public function test_getRules()
    {
        $filter = new LogicalFilter();
        $filter->and_('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            (new InRule('field', ['a', 'b', 'c']))->toArray(),
            $filter->getRules()->toArray()
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

        // copy filter with no rule
        $filter = new LogicalFilter();
        $filter->copy();

        $this->assertNull( $filter->getRules() );
    }

    /**
     */
    public function test_saveAs()
    {
        $filter = new LogicalFilter([
            'or',
            ['field_6', 'equal', 'b'],
        ]);

        $returned_filter = $filter->saveAs( $filter2 );

        $this->assertSame($filter, $filter2);
        $this->assertSame($filter, $returned_filter);
    }

    /**
     */
    public function test_saveCopyAs()
    {
        $filter = new LogicalFilter([
            'or',
            ['field_6', 'equal', 'b'],
        ]);

        $returned_filter = $filter->saveCopyAs( $filter2 );

        $this->assertSame($filter, $returned_filter);

        $this->assertNotEquals(
            spl_object_hash($filter->getRules(false)),
            spl_object_hash($filter2->getRules(false))
        );

        $this->assertEquals(
            $filter->toArray(),
            $filter2->toArray()
        );
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
    public function test_renameFields()
    {
        $filter = new LogicalFilter(
            ['and',
                ['or',
                    ['field_5', '>', 'a'],
                    ['field_3', '<', 'a'],
                ],
                ['not',
                    ['and',
                        ['field_5', '>', 'a'],
                        ['field_4', '=', 'a'],
                    ],
                ],
                ['field_5', 'in', ['a', 'b', 'c']],
            ]
        );

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_five', '>', 'a'],
                    ['field_three', '<', 'a'],
                ],
                ['not',
                    ['and',
                        ['field_five', '>', 'a'],
                        ['field_4', '=', 'a'],
                    ],
                ],
                ['field_five', 'in', ['a', 'b', 'c']],
            ],
            $filter
                ->copy()
                ->renameFields([
                    'field_5' => 'field_five',
                    'field_3' => 'field_three',
                ])
                // ->dump(true)
                ->toArray()
        );

        $this->assertEquals(
            ['and',
                ['or',
                    ['property_5', '>', 'a'],
                    ['property_3', '<', 'a'],
                ],
                ['not',
                    ['and',
                        ['property_5', '>', 'a'],
                        ['property_4', '=', 'a'],
                    ],
                ],
                ['property_5', 'in', ['a', 'b', 'c']],
            ],
            $filter
                ->copy()
                ->renameFields(function($field) {
                    return str_replace('field_', 'property_', $field);
                })
                // ->dump(true)
                ->toArray()
        );

        try {
            $filter->renameFields('sdfghjk');
            $this->assertTrue(false, "An exception should be throw here");
        }
        catch (\InvalidArgumentException $e) {
            // InvalidArgumentException: Minimum parameter must be a scalar
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_add_InRule()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']],
            null,
            ['inrule.simplification_threshold' => 20]
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
            $filter
                // ->dump(!true)
                ->simplify([
                    // 'stop_after' =>
                    // AbstractOperationRule::remove_negations,
                    // AbstractOperationRule::rootify_disjunctions,
                    // AbstractOperationRule::unify_atomic_operands,
                    // AbstractOperationRule::remove_invalid_branches,
                    'in.normalization_threshold' => 6
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_setDefaultOptions()
    {
        $default_inrule_threshold = LogicalFilter::getDefaultOptions()['in.normalization_threshold'];

        LogicalFilter::setDefaultOptions([
            'in.normalization_threshold' => 20,
        ]);

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
            $filter
                ->simplify([

                ])
                // ->dump(true)
                ->toArray()
        );

        LogicalFilter::setDefaultOptions([
            'in.normalization_threshold' => $default_inrule_threshold,
        ]);
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
                ->negateOperand(false, [])
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
                // ->dump(true)
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
    public function test_BelowRule_and_AboveRule_are_strictly_compared()
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

        $result = VisibilityViolator::callHiddenMethod(
            $filter->getRules(), 'forceLogicalCore'
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['not', ['field_1', '=', 3]],
                ],
            ],
            $result->toArray()
        );
    }

    /**
     * @todo debug the runInseparateProcess of php to test the exit call.
     * @ runInSeparateProcess
     */
    public function test_dump_export()
    {
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'export'])
            ;
        $dump = ob_get_clean();

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
            ->dump(false, ['mode' => 'export', 'show_instance' => true])
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
    }

    /**
     */
    public function test_dump_dump()
    {
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'dump'])
            ;
        $dump = ob_get_clean();
        // echo $dump;
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
array(3) {
  [0]=>
  string(7) \"field_1\"
  [1]=>
  string(1) \"=\"
  [2]=>
  int(3)
}


                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

    }

    /**
     */
    public function test_dump_xdebug()
    {
        if ( ! function_exists('xdebug_is_enabled')) {
            $this->markTestSkipped();
        }
        if ( ! xdebug_is_enabled()) {
            $this->markTestSkipped();
        }

        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'xdebug'])
            ;
        $dump = ob_get_clean();
        // echo $dump;
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
array(3) {
  [0] =>
  string(7) \"field_1\"
  [1] =>
  string(1) \"=\"
  [2] =>
  int(3)
}


                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );
    }

    /**
     */
    public function test_dump_string()
    {
        ob_start();
        $filter = (new LogicalFilter( ['and', ['field_1', '=', 3], ['field_2', '!=', null]] ))
            ->dump(false, ['mode' => 'string', 'indent_unit' => '  '])
            ;
        $dump = ob_get_clean();
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
['and',
  ['field_1', '=', 3],
  ['field_2', '!=', NULL],
]

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
    public function test_add_RegexpRule()
    {
        $filter = new LogicalFilter(
            ['field_1', 'regexp', "/^prefix-[^-]+-suffix$/"]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', 'regexp', "/^prefix-[^-]+-suffix$/"],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_toString()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_1', '=', 3],
                    ['field_1', '!=', 100],
                    ['field_1', '>', 20],
                ],
                ['not',
                    ['field_2', '<', -5],
                ],
                ['field_1', 'regexp', "/^prefix-[^-]+-suffix$/"],
                ['field_3', 'in', [2, null]],
                ['field_4', '!in', [4, 12]],
                ['field_5', '<=', 3],
                ['field_5', '>=', 12],
                ['field_6', '><', [20, 30]],
                ['field_6', '=><', [20, 30]],
                ['field_6', '=><=', [20, 30]],
                ['field_6', '><=', [20, 30]],
                ['date', '>', new \DateTime("2018-07-19")],
            ]
        ))
        // ->dump(true)
        ;

        // This call is just meant to expose possible cache collision with toArray
        $filter->toArray();

        $this->assertEquals(
"['and',
    ['or',
        ['field_1', '=', 3],
        ['field_1', '!=', 100],
        ['field_1', '>', 20],
    ],
    ['not',
        ['field_2', '<', -5],
    ],
    ['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],
    ['field_3', 'in', [2, NULL]],
    ['field_4', '!in', [4, 12]],
    ['field_5', '<=', 3],
    ['field_5', '>=', 12],
    ['field_6', '><', [20, 30]],
    ['field_6', '=><', [20, 30]],
    ['field_6', '=><=', [20, 30]],
    ['field_6', '><=', [20, 30]],
    ['date', '>', DateTime::__set_state(array(
       'date' => '2018-07-19 00:00:00.000000',
       'timezone_type' => 3,
       'timezone' => 'UTC',
    ))],
]",
            $filter->toString(['indent_unit' => "    "])
        );

        // toArray must be iso to the provided descrition
        // echo $filter->toString()."\n\n";
        $this->assertEquals(
"['and',['or',['field_1', '=', 3],['field_1', '!=', 100],['field_1', '>', 20],],['not', ['field_2', '<', -5],],['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],['field_3', 'in', [2, NULL]],['field_4', '!in', [4, 12]],['field_5', '<=', 3],['field_5', '>=', 12],['field_6', '><', [20, 30]],['field_6', '=><', [20, 30]],['field_6', '=><=', [20, 30]],['field_6', '><=', [20, 30]],['date', '>', DateTime::__set_state(array(
   'date' => '2018-07-19 00:00:00.000000',
   'timezone_type' => 3,
   'timezone' => 'UTC',
))],]",
            $filter->toString()
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
"['and',['or',['field_1', '=', 3],['field_1', '!=', 100],['field_1', '>', 20],],['not', ['field_2', '<', -5],],['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],['field_3', 'in', [2, NULL]],['field_4', '!in', [4, 12]],['field_5', '<=', 3],['field_5', '>=', 12],['field_6', '><', [20, 30]],['field_6', '=><', [20, 30]],['field_6', '=><=', [20, 30]],['field_6', '><=', [20, 30]],['date', '>', DateTime::__set_state(array(
   'date' => '2018-07-19 00:00:00.000000',
   'timezone_type' => 3,
   'timezone' => 'UTC',
))],]",
            $filter . ''
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
            $filter3->toArray()
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
    public function test_invoke()
    {
        $row_to_match = [
            'field_1' => 8,
            'field_2' => 3,
        ];

        $row_to_mismatch = [
            'field_1' => 12,
            'field_2' => 4,
        ];

        $filter = (new LogicalFilter(
            ['field_2', '!=', 4]
        ))
        // ->dump(true)
        ;

        $this->assertTrue(
            $filter( $row_to_match )
                // ->dump()
                ->hasSolution()
        );

        $this->assertFalse(
            $filter( $row_to_mismatch )
        );

    }

    /**
     */
    public function test_array_filter()
    {
        $array = [
            [
                'field_1' => 8,
                'field_2' => 3,
            ],
            [
                'field_1' => 12,
                'field_2' => 4,
            ],
        ];

        $this->assertEquals(
            [
                [
                    'field_1' => 8,
                    'field_2' => 3,
                ],
            ],
            array_filter( $array, new LogicalFilter(
                ['field_2', '!=', 4]
            ))
        );
    }

    /**
     */
    public function test_validates()
    {
        $filter = (new LogicalFilter(
            [
                [value(), '=', 4],
                'or',
                [key(), '=', 'index1'],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertTrue(
            $filter->validates( 3, 'index1' )
                // ->dump(!true)
                ->hasSolution()
        );

        $this->assertTrue(
            $filter->validates( 4, 'invalid_key' )
                // ->dump(!true)
                ->hasSolution()
        );

        $this->assertFalse(
            $filter->validates( 5, 'invalid_key' )
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
    public function test_getSemanticId()
    {
        $filter = new LogicalFilter;

        $filter
            ->and_('field', 'in', [])
            ->and_('field2', '=', 'dfghjkl')
            ;

        $this->assertEquals(
            '460c39ed20e85bc0dcafc28b5e4e5d4d-511711247b38d5ed3ee96dee4d3bf89a',
            $filter
                // ->dump()
                ->getSemanticId()
        );
    }

    /**
     * /
    public function test_action_on_value()
    {
        $filter = (new LogicalFilter(
            [
                // [function($row, $key) {
                    // return $row['col1'] + $row['col2'];
                // }, '=', 4],
                [value()['col_1'], '=', 'lololo'],
                'or',
                [key(), '=', 'key1'],
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
            // ...
        ];

        var_dump( $filter( $array ) );
    }

    /**/
}
