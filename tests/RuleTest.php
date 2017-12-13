<?php
namespace JClaveau\CustomFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_negateOperand()
    {
        // NullRule
        $rule = new NotRule(
            new NullRule
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new NotNullRule(), $new_rule);


        // NotNullRule
        $rule = new NotRule(
            new NotNullRule
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new NullRule(), $new_rule);


        // AboveRule
        $rule = new NotRule(
            new AboveRule(3)
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new BelowRule(3),
            new EqualRule(3)
        ]), $new_rule);


        // BelowRule
        $rule = new NotRule(
            new BelowRule(3)
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new AboveRule(3),
            new EqualRule(3)
        ]), $new_rule);


        // NotRule
        $rule = new NotRule(
            new NotRule(new BelowRule(3))
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new BelowRule(3), $new_rule);


        // EqualRule
        $rule = new NotRule(
            new EqualRule(3)
        );
        $new_rule = $rule->negateOperand();
        // var_dump($new_rule);
        // exit;
        $this->assertEquals(new OrRule([
            new AboveRule(3),
            new BelowRule(3),
        ]), $new_rule);


        // AndRule (2 operands only)
        $rule = new NotRule(
            new AndRule([
                new EqualRule(3),
                new AboveRule(10),
            ])
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new OrRule([
            new AndRule([
                new EqualRule(3),
                new NotRule(
                    new AboveRule(10)
                ),
            ]),
            new AndRule([
                new NotRule(
                    new EqualRule(3)
                ),
                new AboveRule(10)
            ]),
            new AndRule([
                new NotRule(
                    new EqualRule(3)
                ),
                new NotRule(
                    new AboveRule(10)
                ),
            ]),
        ]), $new_rule);


        // OrRule (2 operands only)
        $rule = new NotRule(
            new OrRule([
                new EqualRule(3),
                new AboveRule(10),
            ])
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new AndRule([
            new NotRule(
                new EqualRule(3)
            ),
            new NotRule(
                new AboveRule(10)
            ),
        ]), $new_rule);


        // InRule
        $rule = new NotRule(
            new InRule([3, 10])
        );
        $new_rule = $rule->negateOperand();
        $this->assertEquals(new AndRule([
            new NotRule(
                new EqualRule(3)
            ),
            new NotRule(
                new EqualRule(10)
            ),
        ]), $new_rule);

    }

    /**/
}
