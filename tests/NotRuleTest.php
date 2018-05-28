<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class NotRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_negateOperand()
    {
        // NullRule
        $rule = new NotRule(
            new NullRule('field')
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new NotNullRule('field'), $new_rule);


        // NotNullRule
        $rule = new NotRule(
            new NotNullRule('field')
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new NullRule('field'), $new_rule);


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


        // AndRule (2 operands only)
        $rule = new NotRule(
            new AndRule([
                new EqualRule('field', 3),
                new AboveRule('field', 10),
            ])
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
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
                new AboveRule('field', 10)
            ]),
            new AndRule([
                new NotRule(
                    new EqualRule('field', 3)
                ),
                new NotRule(
                    new AboveRule('field', 10)
                ),
            ]),
        ]), $new_rule);


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

        // var_dump('$new_rule');
        // var_dump($new_rule);

        // var_dump('$expected');
        // var_dump($expected);

        // var_dump('$expected == $new_rule');
        // var_dump($expected == $new_rule);
        // exit;

        $this->assertEquals($expected, $new_rule);

    }

    /**/
}
