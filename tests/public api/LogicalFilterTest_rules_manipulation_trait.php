<?php
namespace JClaveau\LogicalFilter;


trait LogicalFilterTest_rules_manipulation_trait
{
    /**
     */
    public function test_removeRules_simple()
    {
        /**/
        // field
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
            ->removeRules(['field', '=', 'field_5'])
            // ->dump(true)
            ->toArray()
        );

        // operator
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

        // value
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

        // children
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
            ->removeRules(
                ['children', '=', 4]
            )
            // ->dump(true)
            ->toArray()
        );
        /**/

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

        // TODO path
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
    public function test_filterRules_multiple()
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

        $rules = $filter->listRulesMatching([
            ['field', '=', 'field_5'],
            'and',
            ['operator', '=', '>'],
        ])
        ;

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
        $rules = $filter->listRulesMatching([
            ['field', '=', 'field_5'],
            'and',
            ['operator', '=', '>'],
        ], false)
        ;

        $this->assertSame(
            $filter->getRules(false)
                ->getOperands()[1]
                ->dump()
                ,
            $rules[0]
        );
    }

    /**/
}
