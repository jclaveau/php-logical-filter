<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class RuleFiltererTest extends \AbstractTest
{
    /**
     */
    public function test_filtering_cases()
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
                ['field_4', '><', ['a', 'z']],
                ['field_5', '<=', 16],
                ['field_5', '>=', -5],
                ['field_6', '>=', -5],
                ['field_7', '=', 'plop'],
            ]
        );

        $filtered_rules = (new RuleFilterer)
        ->apply(
            new LogicalFilter(
                ['or',
                    ['and',
                        ['or',
                            ['field', '=', 'field_2'],
                            ['field', 'regexp', '/^field_4$/'],
                        ],
                        ['operator', '!=', '!='],
                        ['operator', '!in', ['=><', '><=']],
                        ['field', '<=', 'field_4'],
                        ['field', '>=', 'field_1'],
                    ],
                    ['and',
                        ['value', '!=', null],
                        ['field', '=', 'field_6'],
                    ],
                    ['and',
                        ['description', '=', ['field_7', '=', 'plop']],
                    ],
                ]
            ),
            $filter_to_filter->getRules()
        )
        // ->dump(true)
        ;

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_4', '><', ['a', 'z']],
                ['field_6', '>=', -5],
                ['field_7', '=', 'plop'],
            ],
            $filtered_rules->toArray()
        );
    }

    /**
     */
    public function test_RuleFilter_with_rule_on_unhandled_property()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
            ]
        );

        try {
            $filtered_rules = (new RuleFilterer)->apply(
                new LogicalFilter(
                    ['or',
                        ['unhandled_property', '=', ['field_7', '=', 'plop']],
                    ]
                ),
                $filter_to_filter->getRules()
            );

            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "Rule filters must belong to ["
                . implode(', ', ['field', 'operator', 'value', 'depth', 'description', 'children', 'path'])
                ."] contrary to : 'unhandled_property'",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_apply_with_bad_argument()
    {
        try {
            $filtered_rules = (new RuleFilterer)->apply(
                new LogicalFilter(
                    ['or',
                        ['field', '=', 'whatever'],
                    ]
                ),
                'not a rule tree'
            );

            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "\$ruleTree_to_filter must be an array or an AbstractRule instead of: 'not a rule tree'",
                $e->getMessage()
            );
        }
    }

    /**/
}
