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
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new BelowRule('field', 3),
            new EqualRule('field', 3)
        ]), $new_rule);


        // BelowRule
        $rule = new NotRule(
            new BelowRule('field', 3)
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new AboveRule('field', 3),
            new EqualRule('field', 3)
        ]), $new_rule);


        // NotRule
        $rule = new NotRule(
            new NotRule(new BelowRule('field', 3))
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new BelowRule('field', 3), $new_rule);


        // EqualRule
        $rule = new NotRule(
            new EqualRule('field', 3)
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new AboveRule('field', 3),
            new BelowRule('field', 3),
        ]), $new_rule);

        // EqualRule null
        $rule = new NotRule(
            new EqualRule('field', null)
        );
        $new_rule = $rule->negateOperand();
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
                ->negateOperand()
                ->toArray()
        );

        // OrRule (2 operands only)
        $rule = new NotRule(
            new OrRule([
                new EqualRule('field', 3),
                new AboveRule('field', 10),
            ])
        );

        $new_rule = $rule->negateOperand();
        $expected = new AndRule([
            new NotRule(
                new EqualRule('field', 3)
            ),
            new NotRule(
                new AboveRule('field', 10)
            ),
        ]);

        $this->assertEquals($expected, $new_rule);

        // InRule
        $rule = new NotRule(
            new InRule('field', [3, 10])
        );
        $new_rule = $rule->negateOperand();
        $expected = new AndRule([
            new NotRule(
                new EqualRule('field', 3)
            ),
            new NotRule(
                new EqualRule('field', 10)
            ),
        ]);

        $this->assertEquals($expected, $new_rule);

    }

    /**
     */
    public function test_negateOperand_with_bad_operand_type()
    {
        $rule = new NotRule();
        VisibilityViolator::setHiddenProperty($rule, 'operands', ['dsfghjk']);

        try {
            $rule->negateOperand();
            $this->assertTrue(
                false, "NotRule should have thrown an exceptrion as its "
                . "operand is neither null either an AbstractRule"

            );
        }
        catch (\LogicException $e) {
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**/
}
