<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class NotRuleTest extends \AbstractTest
{
    /**
     */
    public function test_negateOperand()
    {
        // AboveRule
        $rule = new NotRule(
            new AboveRule('field', 3)
        );
        $new_rule = $rule->negateOperand(false, []);
        $this->assertEquals(new OrRule([
            new BelowRule('field', 3),
            new EqualRule('field', 3)
        ]), $new_rule);


        // BelowRule
        $rule = new NotRule(
            new BelowRule('field', 3)
        );
        $new_rule = $rule->negateOperand(false, []);
        $this->assertEquals(new OrRule([
            new AboveRule('field', 3),
            new EqualRule('field', 3)
        ]), $new_rule);


        // NotRule
        $rule = new NotRule(
            new NotRule(new BelowRule('field', 3))
        );
        $new_rule = $rule->negateOperand(false, []);
        $this->assertEquals((new BelowRule('field', 3))->toArray(), $new_rule->toArray());


        // EqualRule
        $rule = new NotRule(
            new EqualRule('field', 3)
        );
        $new_rule = $rule->negateOperand(false, [
            'not_equal.normalization' => true
        ]);
        $this->assertEquals(new OrRule([
            new AboveRule('field', 3),
            new BelowRule('field', 3),
        ]), $new_rule);

        // EqualRule
        $rule = new NotRule(
            new EqualRule('field', 3)
        );
        $new_rule = $rule->negateOperand(false, [
            'not_equal.normalization' => false
        ])
        // ->dump(true)
        ;
        $this->assertEquals(new NotEqualRule('field', 3), $new_rule);

        // EqualRule null
        $rule = new NotRule(
            new EqualRule('field', null)
        );
        $new_rule = $rule->negateOperand(false, []);
        $this->assertEquals(
            new NotEqualRule('field', null),
            $new_rule
        );

        // AndRule (2 operands only)
        $rule = new NotRule(
            new AndRule([
                new EqualRule('field', 3),
                new AboveRule('field', 10),
            ])
        );

        $this->assertEquals(
            (new OrRule([
                new AndRule([
                    new NotRule(
                        new EqualRule('field', 3)
                    ),
                    new AboveRule('field', 10),
                ]),
                new AndRule([
                    new EqualRule('field', 3),
                    new NotRule(
                        new AboveRule('field', 10)
                    ),
                ]),
                new AndRule([
                    new NotRule(
                        new EqualRule('field', 3)
                    ),
                    new NotRule(
                        new AboveRule('field', 10)
                    ),
                ]),
            ]))
            ->toArray(),
            $rule
                ->negateOperand(false, [])
                ->toArray()
        );

        // OrRule (2 operands only)
        $rule = new NotRule(
            new OrRule([
                new EqualRule('field', 3),
                new AboveRule('field', 10),
            ])
        );

        $new_rule = $rule->negateOperand(false, [])
            // ->dump()
            ;
        $expected = new AndRule([
            new NotEqualRule('field', 3),
            new NotRule(
                new AboveRule('field', 10)
            ),
        ]);

        $this->assertEquals($expected, $new_rule);

        // InRule
        $rule = new NotRule(
            new InRule('field', [3, 10])
        );
        $new_rule = $rule->negateOperand(false, [
            'not_equal.normalization'    => false,
            'in.normalization_threshold' => 3,
        ])
        // ->dump()
        ;
        $expected = new AndRule([
            new NotEqualRule('field', 3),
            new NotEqualRule('field', 10),
        ]);

        $this->assertEquals(
            $expected->toArray(),
            $new_rule->toArray()
        );

    }

    /**
     */
    public function test_negateOperand_with_bad_operand_type()
    {
        $rule = new NotRule();
        VisibilityViolator::setHiddenProperty($rule, 'operands', ['dsfghjk']);

        try {
            $rule->negateOperand(false, []);
            $this->assertTrue(
                false, "NotRule should have thrown an exceptrion as its "
                . "operand is neither null either an AbstractRule"

            );
        }
        catch (\LogicException $e) {
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_setOperandsOrReplaceByOperation()
    {
        $rule = new NotRule();
        $rule = $rule->setOperandsOrReplaceByOperation([
            new EqualRule('field', 3)
        ], []);

        $this->assertEquals(
            ['field', '!=', 3],
            $rule
                // ->dump()
                ->toArray()
        );

        $rule = $rule->setOperandsOrReplaceByOperation([
            new NotRule(new EqualRule('field', 3))
        ], []);

        $this->assertEquals(
            ['field', '=', 3],
            $rule
                // ->dump()
                ->toArray()
        );

        $rule = new NotRule();
        try {
            $rule = $rule->setOperandsOrReplaceByOperation([
                new EqualRule('field', 3),
                new EqualRule('field_2', 4),
            ], []);
            $this->markTestIncomplete("An exception has not been thrown here");
        }
        catch (\InvalidArgumentException $e) {
            $this->assertRegexp(
                "/^Negations can handle only one operand instead of: /",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_toArray()
    {
        $filter = (new \JClaveau\LogicalFilter\LogicalFilter(
            ['or',
                ['field', '=', 'lalala'],
            ]
        ))
        // ->dump(true)
        ;

        $filter_2 = (new \JClaveau\LogicalFilter\LogicalFilter(
            ['not', $filter]
        ))
        // ->dump(true)
        ;

        // Caching toArray produced dump with semantic ids instead of
        // rules description
        $this->assertEquals(
            ['not',
                ['or',
                    ['field', '=', 'lalala'],
                ],
            ],
            $filter_2->toArray()
        );
    }

    /**/
}
