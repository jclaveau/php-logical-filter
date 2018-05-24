<?php
namespace JClaveau\LogicalFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class AbstractOperationRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * (A && ) <=> A
     * (A || ) <=> A
     */
    public function test_simplify_oneOperand()
    {
        $above = new AboveRule('field_name', 2);

        // OrRule
        $orRule = new OrRule([$above]);
        $this->assertEquals(
            $above,
            $orRule->simplify()
        );

        // AndRule
        $andRule = new AndRule([$above]);
        $this->assertEquals(
            $above,
            $andRule->simplify()
        );
    }

    /**
     * When an AndRule has operands which are AndRules, they can be merged. The
     * same simplifaction can be done for OrRule.
     *
     * (A || B) || (C || D) <=> A || B || C || D
     * (A && B) && (C && D) <=> A && B && C && D
     */
    public function test_simplify_sameTypeOperand()
    {
        $above1 = new AboveRule('field_name1', 2);
        $above2 = new AboveRule('field_name2', 2);
        $above3 = new AboveRule('field_name3', 2);
        $above4 = new AboveRule('field_name4', 2);

        // OrRule
        $subOrRule1 = new OrRule([$above1, $above2]);
        $subOrRule2 = new OrRule([$above3, $above4]);
        $orRule = new OrRule([$subOrRule1, $subOrRule2]);

        $expectedOrRule = new OrRule([
            $above1,
            $above2,
            $above3,
            $above4,
        ]);

        $this->assertEquals(
            $expectedOrRule,
            $orRule->simplify()
        );

        // AndRule
        $subAndRule1 = new AndRule([$above1, $above2]);
        $subAndRule2 = new AndRule([$above3, $above4]);
        $andRule = new AndRule([$subAndRule1, $subAndRule2]);

        $expectedAndRule = new AndRule([
            $above1,
            $above2,
            $above3,
            $above4,
        ]);

        $this->assertEquals(
            $expectedAndRule,
            $andRule->simplify()
        );
    }

    /**/
}
