<?php
namespace JClaveau\LogicalFilter;
use       JClaveau\LogicalFilter\Filterer\RuleFilterer;
use       JClaveau\LogicalFilter\Rule\InRule;
use       JClaveau\LogicalFilter\Rule\AbstractRule;


trait LogicalFilterTest_rules_manipulation_trait
{
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
            ['and'],
            (new LogicalFilter(
                ['and',
                    ['or',
                        ['field_3', '<', 'a'],
                        ['field_5', '=', 12],
                        ['field_4', '=', 12],
                        ['field_6', '=', 12],
                    ],
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
     * TODO make this test work
     * /
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
                'or',
                ['value', '=', 'laliloulilala'],
                ['and',
                    ['field', '=', 'field_5'],
                    ['operator', '=', '>'],
                    // ['depth', '=', 1],  // do not work
                ],
                // ['operator', 'in', ['>=', '<=']],
                // ['children', '>', 7],
                // ['description', '=', ['field_8', '=', 8]], // TODO requires disabling negation of arrays
            ])
            ->dump(!true)
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
                        ['field_3', '>', 'a'],
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
            $filtered_filter->toArray()
        );
    }

    /**
     */
    public function test_keepLeafRulesMatching_on_negation()
    {
        $filtered_filter = (new LogicalFilter(
            ["id", "!in", [299,298]]
        ))
        ->keepLeafRulesMatching([
            'and',
            ['field', '=', 'other_field_name'],
        ])
        // ->dump(true)
        ;

        $this->assertEquals(
            null, // TODO this would become TrueRule
            $filtered_filter->toArray()
        );
    }

    /**/
}
