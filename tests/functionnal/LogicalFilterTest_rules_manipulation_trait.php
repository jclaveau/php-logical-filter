<?php
namespace JClaveau\LogicalFilter;
use       JClaveau\LogicalFilter\Filterer\RuleFilterer;
use       JClaveau\LogicalFilter\Rule\InRule;
use       JClaveau\LogicalFilter\Rule\AbstractRule;


trait LogicalFilterTest_rules_manipulation_trait
{
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
                ['field_6', '>=', 'a'],
                ['field_7', '<=', 'a'],
                ['field_8', '><', ['a', 'Z']],
                ['field_8', '=><', ['a', 'Z']],
                ['field_8', '=><=', ['a', 'Z']],
                ['field_8', '><=', ['a', 'Z']],
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
                ['field_6', '>=', 'a'],
                ['field_7', '<=', 'a'],
                ['field_8', '><', ['a', 'Z']],
                ['field_8', '=><', ['a', 'Z']],
                ['field_8', '=><=', ['a', 'Z']],
                ['field_8', '><=', ['a', 'Z']],
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
                ['property_6', '>=', 'a'],
                ['property_7', '<=', 'a'],
                ['property_8', '><', ['a', 'Z']],
                ['property_8', '=><', ['a', 'Z']],
                ['property_8', '=><=', ['a', 'Z']],
                ['property_8', '><=', ['a', 'Z']],
            ],
            $filter
                ->copy()
                ->renameFields(function($field) {
                    return str_replace('field_', 'property_', $field);
                })
                // ->dump(!true)
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
    public function test_removeRules_simple_field()
    {
        $this->assertEquals(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                ],
            ],
            (new LogicalFilter(
                ['and',
                    [
                        ['field_3', '<', 'a'],
                        'or',
                        ['field_5', '=', 12],
                        'or',
                        ['field_5', '=', 12],
                        'or',
                        ['field_5', '=', 12],
                        'or',
                        ['field_5', '=', 12],
                    ],
                ]
            ))
            ->removeRules(['field', '=', 'field_5'])
            // ->dump(true)
            ->toArray()
        );
    }

    /**
     */
    public function test_removeRules_simple_operator()
    {
        $this->assertEquals(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                ],
            ],
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                ]
            ))
            ->removeRules(
                ['operator', '=', '=']
            )
            // ->dump(true)
            ->toArray()
        );
    }

    /**
     */
    public function test_removeRules_simple_value()
    {
        $this->assertEquals(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                ],
            ],
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                ]
            ))
            ->removeRules(
                ['value', '=', 12]
            )
            // ->dump(true)
            ->toArray()
        );
    }

    /**
     */
    public function test_removeRules_simple_children()
    {
        $this->assertEquals(
            ['and',
                ['field_6', '=', 12],
            ],
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                        ['field_4', '=', 12],
                        ['field_6', '=', 12],
                    ],
                    ['field_6', '=', 12],
                ]
            ))
            // ->dump(!true)
            ->removeRules(
                ['children', '=', 4]
            )
            // ->dump(true)
            ->toArray()
        );
    }


    /**
     */
    public function test_removeRules_simple_description()
    {
        // TODO description
        // $this->assertEquals(
            // ['and',
                // ['or',
                    // ['field_5', '=', 12],
                // ],
            // ],
            // $filter
                // ->removeRules(
                    // ['description', '=', ['field_3', '<', 'a']]
                // )
                // ->dump(true)
                // ->toArray()
        // );
    }

    /**
     */
    public function test_removeRules_simple_depth()
    {
        // TODO depth
        try {
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                ]
            ))
            // ->dump(true)
            ->removeRules(
                ['depth', '=', 1]
            )
            // ->dump(true)
            ;

            $this->assertTrue(
                false,
                'No exception thrown trying filtering on depth'
            );
        }
        catch (\InvalidArgumentException $e) {
           $this->assertTrue(true, "Exception thrown: ". $e->getMessage());
        }
    }


    /**
     */
    public function test_removeRules_simple_path()
    {
        try {
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                    ['or',
                        ['field_4', '<', 25],
                        ['field_6', '>=', 4],
                    ],
                ]
            ))
            // ->dump(true)
            ->removeRules(
                ['path', '=', 1]
            )
            // ->dump(true)
            ;

            $this->assertTrue(
                false,
                'No exception thrown trying filtering on depth'
            );
        }
        catch (\InvalidArgumentException $e) {
           $this->assertTrue(true, "Exception thrown: ". $e->getMessage());
        }
    }

    /**
     */
    public function test_removeRules_multiple()
    {
        $this->assertEquals(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                ],
            ],
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                    ['field_5', '>', 11],
                ]
            ))
            ->removeRules([
                ['field', '=', 'field_5'],
                'and',
                ['operator', '=', '>'],
            ])
            // ->dump(!true)
            ->toArray()
        );
    }

    /**
     */
    public function test_removeRules_operator_above()
    {
        $this->assertEquals(
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                    ],
                    ['field_5', '>', 11],
                ]
            ))
            ->removeRules([
                ['field', '=', 'field_5'],
                'and',
                ['operator', '=', '>'],
            ])
            // ->dump(!true)
            ->toArray()
            ,
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                ],
            ]
        );
    }

    /**
     */
    public function test_removeRules_complex()
    {
        $this->assertEquals(
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '>', 'b'],
                        // ['field_6', '<=', 45],
                    ],
                    ['not',
                        ['and',
                            ['field_5', '>', 'c'],
                            ['field_4', '=', 'd'],
                            // ['field_6', '>=', 'a'],
                            ['field_3', '>', 'e'],
                        ],
                    ],
                    ['field_5', 'in', ['a', 'b', 'c']],
                    ['field_7', '>', 'laliloulilala'],
                    ['field_8', '=', 8],
                    // ['and',
                        // ['or'], ['or'], ['or'], ['or'], ['or'], ['or'], ['or'], ['or'],
                    // ],
                ]
            ))
            ->removeRules([
                ['value', '=', 'laliloulilala'],
                'or',
                ['and',
                    ['field', '=', 'field_5'],
                    ['operator', '=', '>'],
                    // ['depth', '=', 1],  // do not work
                ],
                // ['operator', 'in', ['>=', '<=']],
                // ['children', '>', 7],
                // ['description', '=', ['field_8', '=', 8]], // TODO requires disabling negation of arrays
            ])
            // ->dump(!true)
            ->toArray()
            ,
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    // ['field_5', '>', 'b'],
                    // ['field_6', '<=', 45],
                ],
                ['not',
                    ['and',
                        // ['field_5', '>', 'c'],
                        ['field_4', '=', 'd'],
                        // ['field_6', '>=', 'e'],
                        ['field_3', '>', 'e'],
                    ],
                ],
                ['field_5', 'in', ['a', 'b', 'c']],
                // ['field_7', '>', 'laliloulilala'],
                ['field_8', '=', 8],
                // ['and',
                    // ['or'], ['or'], ['or'], ['or'], ['or'], ['or'], ['or'], ['or'],
                // ],
            ]
        );
    }

    /**
     */
    public function test_removeRules_throwing_exception()
    {
        // As TrueRule is not implemented, we cannot replace a predicate
        // by True so if the parent operation as only one operand, removing
        // it will produce a false négative as ['and'] <=> false
        try {
            (new LogicalFilter(
                ['and',
                    ['field_3', '<', 'a'],
                ]
            ))
            ->removeRules(
                ['and',
                    ['field', '=', 'field_3'],
                ]
            )
            // ->dump()
            ;
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "Removing the only rule ['field_3', '<', 'a'] from the filter ['and',['field_3', '<', 'a'],] produces a case which has no possible solution due to missing implementation of TrueRule.\nPlease see: https://github.com/jclaveau/php-logical-filter/issues/59",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_listLeafRulesMatching_empty()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                ],
                ['field_5', '>', 11],
            ]
        ));

        $rules = $filter->listLeafRulesMatching();

        $this->assertEquals(
            [
                ['field_3', '<', 'a'],
                ['field_5', '=', 12],
                ['field_5', '>', 11],
            ],
            [
                $rules[0]->toArray(),
                $rules[1]->toArray(),
                $rules[2]->toArray(),
            ]
        );
    }

    /**
     */
    public function test_listLeafRulesMatching_of_an_empty_filter()
    {
        $filter = new LogicalFilter();

        $rules = $filter->listLeafRulesMatching();

        $this->assertEquals( [], $rules );
    }

    /**
     */
    public function test_listLeafRulesMatching_multiple()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                ],
                ['field_5', '>', 11],
            ]
        ));

        $rules = $filter->listLeafRulesMatching([
            'and',
                ['field', '=', 'field_5'],
                ['operator', '=', '>'],
        ]);

        $this->assertEquals( 1, count($rules) );

        $this->assertEquals(
            ['field_5', '>', 11],
            $rules[0]->toArray()
        );

        // checks that the filtered row is a reference of the one in the filter
        $this->assertEquals(
            $filter->getRules(false)
                ->getOperands()[1]
                // ->dump()
            ,
            $rules[0]
        );

        // copying rules
        $rules = $filter->listLeafRulesMatching([
            ['field', '=', 'field_5'],
            'and',
            ['operator', 'in', ['>']],
        ], false)
        ;

        $this->assertSame(
            $filter->getRules(false)
                ->getOperands()[1]
                // ->dump()
                ,
            $rules[0]
        );
    }

    /**
     */
    public function test_onEachRule()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                    ['field_6', '>', 11],
                ],
                ['field_5', '>', 8],
            ]
        ))
        ->onEachRule(
            ['operator', '=', '>'],
            function (AbstractRule $rule, $key, array &$siblings) {
                $siblings[ $key ] = new InRule(
                    $rule->getField(), [13, 15, $rule->getMinimum() + 10]
                );
            }
        );

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_3', '<', 'a'],
                    ['field_5', '=', 12],
                    ['field_6', 'in', [13, 15, 21]],
                ],
                ['field_5', 'in', [13, 15, 18]],
            ],
            $filter->toArray()
        );
    }


    /**
     */
    public function test_onEachCase()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field', '=', 'plop'],
                    ['field', '<', 4],
                ],
                ['field2', '>', 1],
            ]
        ))
        // ->dump()
        ;

        $cases = [];
        $filter->onEachCase(function (Rule\AndRule $case) use (&$cases) {
            $cases[] = $case->toArray();
            // Modifying cases
            $case->addOperand(
                (new LogicalFilter(['field3', 'regexp', "#^lalala#"]))->getRules()
            );
        })
        // ->dump(true)
        ;

        $this->assertEquals(
            [
                ['and',
                    ['field', '=', 'plop'],
                    ['field2', '>', 1],
                ],
                ['and',
                    ['field', '<', 4],
                    ['field2', '>', 1],
                ],
            ],
            $cases
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field', '=', 'plop'],
                    ['field2', '>', 1],
                    ['field3', 'regexp', "#^lalala#"],
                ],
                ['and',
                    ['field', '<', 4],
                    ['field2', '>', 1],
                    ['field3', 'regexp', "#^lalala#"],
                ],
            ],
            $filter
                ->toArray()
        );
    }

    /**
     */
    public function test_applyOn_lazy_value_and_lazy_key()
    {
        $filter = (new LogicalFilter(
            ['or',
                [value()['col_1'], '=', 'lololo'],
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
    public function test_applyOn_another_filter()
    {
        $filter_to_filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_2', '!=', null],
        ]);

        $filtered_filter = (new LogicalFilter([
                'and',
                ['field',    '=',  'field_2'],
                ['operator', '!=', '!='],
            ], new RuleFilterer)
        )
        ->applyOn(
            $filter_to_filter
            // no action => remove every non matching rule
        )
        // ->dump()
        ;

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
            ],
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_applyOn_another_filter_with_custom_action()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_2', '!=', null],
            ]
        );

        $filtered_filter = (new LogicalFilter(
            ['and',
                ['field',    '=',  'field_2'],
                ['operator', '!=', '!='],
            ],
            new RuleFilterer
        ))
        ->applyOn( $filter_to_filter, function(AbstractRule $matching_rule, $key, array $siblings) {
            // replace > and < by >= and <=
            if ($matching_rule instanceof AboveRule)
                $value = $matching_rule->getMinimum();
            elseif ($matching_rule instanceof BelowRule)
                $value = $matching_rule->getMaximum();

            $siblings[$key] = new OrRule([
                $matching_rule,
                new EqualRule(
                    $matching_rule->getField(),
                    $value
                )
            ]);
        })
        // ->dump()
        ;

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
            ],
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_keepLeafRulesMatching()
    {
        $filtered_filter = (new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_2', '!=', null],
        ]))
        ->keepLeafRulesMatching(
            ['field', '=', 'field_1']
        )
        // ->dump(true)
        ;

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
            ],
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_keepLeafRulesMatching_on_an_empty_filter()
    {
        $filtered_filter = (new LogicalFilter)
        ->keepLeafRulesMatching(
            ['field', '=', 'field_1']
        )
        // ->dump(true)
        ;

        $this->assertEquals(
            null, // TODO this would become TrueRule
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_keepLeafRulesMatching_filtering_on_in_operator()
    {
        $filtered_filter = (new LogicalFilter(
            ["and",
                ["types", "in", ["my_type_1", "my_type_2"]],
            ]
        ))
        ->keepLeafRulesMatching([
            'and',
            ['field',    '=',  'types'],
            ['operator', 'in', ['=', 'in']],
        ])
        ;

        $this->assertEquals(
            ["and",
                ["types", "in", ["my_type_1", "my_type_2"]],
            ],
            $filtered_filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_keepLeafRulesMatching_with_no_result()
    {
        $filtered_filter = (new LogicalFilter(
            ["and",
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_2', '!=', null],
            ]
        ))
        // ->dump(true)
        ->keepLeafRulesMatching([
            'and',
            ['field', '=', 'field that does not exist'],
        ])
        // ->dump(true)
        ;

        $this->assertEquals(
            ["and"],    // TODO replace it by a FalseRule
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_matches()
    {
        $filter = (new LogicalFilter(
            ["and",
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_2', '!=', null],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertTrue(
            $filter->matches([
                'and',
                ['field_2', '<', -4],
                ['field_2', '!=', null],
            ])
        );

        $this->assertTrue(
            $filter->matches([
                'or',
                ['field_2', '<', -5],
                ['field_2', '>', 6],
            ])
        );

        $this->assertFalse(
            $filter->matches([
                'and',
                ['field_2', '=', 3],
                ['field_3', '=', null],
            ])
        );
    }

    /**
     */
    public function test_getFieldRange_numbers()
    {
        $filter = (new LogicalFilter(
            ["or",
                ['field_2', '=', 2],
                ['and',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_2', '<', 8],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => 2,
                'max'      => 8,
                'nullable' => false,
            ],
            $filter->getFieldRange('field_2')
        );

        $filter = (new LogicalFilter(
            ["or",
                ['field_2', '=', 2],
                ['and',
                    ['field_2', '>', -10],
                    ['field_2', '<', 6],
                ],
                ['field_2', '<', 8],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => -10,
                'max'      => 8,
                'nullable' => false,
            ],
            $filter->getFieldRange('field_2')
        );
    }

    /**
     */
    public function test_getFieldRange_strings()
    {
        $filter = (new LogicalFilter(
            ["or",
                ['field_2', '=', 'dddd'],
                ['and',
                    ['field_2', '>', 'aaaa'],
                    ['field_2', '<', 'rrrr'],
                ],
                ['field_2', '<', 'tttt'],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => 'aaaa',
                'max'      => 'tttt',
                'nullable' => false,
            ],
            $filter->getFieldRange('field_2')
        );
    }

    /**
     */
    public function test_getFieldRange_null()
    {
        $filter = (new LogicalFilter(
            ["or",
                ['field_2', '=', null],
                ['and',
                    ['field_2', '>', -10],
                    ['field_2', '<', 6],
                ],
                ['field_2', '<', 8],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => -10,
                'max'      => 8,
                'nullable' => true,
            ],
            $filter->getFieldRange('field_2')
        );
    }

    /**
     */
    public function test_getFieldRange_DateTimes()
    {
        $filter = (new LogicalFilter(
            ["or",
                ['and',
                    ['field_2', '>', new \DateTimeImmutable('2019-02-06')],
                    ['field_2', '<', new \DateTimeImmutable('2019-02-09')],
                ],
                ['field_2', '<', new \DateTimeImmutable('2019-02-13')],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => new \DateTimeImmutable('2019-02-06'),
                'max'      => new \DateTimeImmutable('2019-02-13'),
                'nullable' => false,
            ],
            $filter->getFieldRange('field_2')
        );
    }

    /**
     */
    public function test_getFieldRange_no_matching_rule()
    {
        $filter = (new LogicalFilter(
            ["or",
                ['and',
                    ['field_2', '>', new \DateTimeImmutable('2019-02-06')],
                    ['field_2', '<', new \DateTimeImmutable('2019-02-09')],
                ],
                ['field_2', '<', new \DateTimeImmutable('2019-02-13')],
            ]
        ));

        $this->assertEquals(
            [
                'min'      => null,
                'max'      => null,
                'nullable' => false,
            ],
            $filter->getFieldRange('field_8')
        );
    }

    /**/
}
