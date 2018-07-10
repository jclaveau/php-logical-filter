<?php
namespace JClaveau\LogicalFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class AbstractOperationRuleTest extends \AbstractTest
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
            $above->toArray(),
            $orRule
                ->simplify()
                // ->dump()
                ->toArray()
        );

        // AndRule
        $andRule = new AndRule([$above]);
        $this->assertEquals(
            $above->toArray(),
            $andRule->simplify()->toArray()
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
            $expectedOrRule->toArray(),
            $orRule
                ->simplify()
                ->toArray()
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
            $expectedAndRule
                // ->dump()
                ->toArray(),
            $andRule
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**
     * ( field > 2 && field > 4 && field > 6 ) <=> field > 6
     * ( field > 2 || field > 4 || field > 6 ) <=> field > 2
     */
    public function test_simplify_keepOneRuleOfEach()
    {

        $above1 = new AboveRule('field_name', 2);
        $above2 = new AboveRule('field_name', 4);
        $above3 = new AboveRule('field_name', 6);

        $below1 = new BelowRule('field_name', 2);
        $below2 = new BelowRule('field_name', 4);
        $below3 = new BelowRule('field_name', 6);

        // AndRule
        $this->assertEquals(
            $above3,
            (new AndRule([$above1, $above2, $above3]))->simplify()
        );

        $this->assertEquals(
            $below1,
            (new AndRule([$below1, $below2, $below3]))->simplify()
        );

        // OrRule
        $this->assertEquals(
            $above1,
            (new OrRule([$above1, $above2, $above3]))->simplify()
        );

        $this->assertEquals(
            $below3,
            (new OrRule([$below1, $below2, $below3]))->simplify()
        );
    }

    /**
     * field = 2 && field = 4 && field = 6 <=> field = 2
     */
    public function test_simplify_removeDuplicatedOperands()
    {
        $equal1 = new EqualRule('field_name', 2);
        $equal2 = new EqualRule('field_name', 2);
        $equal3 = new EqualRule('field_name', 2);

        // AndRule
        $this->assertEquals(
            $equal1,
            (new AndRule([$equal1, $equal2, $equal3]))->simplify()
        );

        // OrRule
        $this->assertEquals(
            $equal1,
            (new OrRule([$equal1, $equal2, $equal3]))->simplify()
        );
    }

    /**
     */
    public function test_removeInvalidBranches_withNegations()
    {
        $above = new AboveRule('field_name', 3);
        $below = new BelowRule('field_name', 1);
        $equal = new EqualRule('field_name', 2);

        $this->assertEmpty(
            (new AndRule([$above, new NotRule($above)]))
                ->simplify( ['stop_before' => AbstractOperationRule::remove_invalid_branches] )
                ->removeInvalidBranches()
                ->getOperands()
        );

        $this->assertEquals(
            $below
                // ->dump(false, false)
                ->toArray(),

            (new OrRule([
                    (new AndRule([$above, new NotRule($above)])),
                    $below,
                ]))
                ->simplify()
                // ->dump(false, false)
                ->toArray()
        );

        // OR with no possibility working
        $this->assertEmpty(
            (new OrRule([
                    (new AndRule([$above, new NotRule($above)])),
                    (new AndRule([$above, $equal])),
                ]))
                ->simplify()
                ->removeInvalidBranches()
                ->getOperands()
        );
    }

    /**/
}
