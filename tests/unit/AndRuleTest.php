<?php
namespace JClaveau\LogicalFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class AndRuleTest extends \AbstractTest
{
    /**
     * + if A = 2 && A > 1 <=> A = 2
     * + if A = 2 && A < 4 <=> A = 2
     */
    public function test_simplify_unifyingDifferendTypesOfOperands()
    {
        $below = new BelowRule('field_name', 3);
        $equal = new EqualRule('field_name', 2);
        $above = new AboveRule('field_name', 1);

        $this->assertEquals(
            $equal->toArray(),
            (new AndRule([$below, $equal, $above]))
                ->simplify()
                ->toArray()
        );
    }

    /**
     */
    public function test_removeInvalidBranches()
    {
        $below = new BelowRule('field_name', 1);
        $equal = new EqualRule('field_name', 2);
        $above = new AboveRule('field_name', 3);

        $this->assertEmpty(
            (new AndRule([$below, $equal]))
                ->simplify( ['stop_before' => AbstractOperationRule::remove_invalid_branches] )
                ->removeInvalidBranches([])
                ->getOperands()
        );

        $this->assertEmpty(
            (new AndRule([$equal, $above]))
                ->simplify( ['stop_before' => AbstractOperationRule::remove_invalid_branches] )
                ->removeInvalidBranches([])
                ->getOperands()
        );

        $this->assertEmpty(
            (new AndRule([$below, $above]))
                ->simplify( ['stop_before' => AbstractOperationRule::remove_invalid_branches] )
                ->removeInvalidBranches([])
                ->getOperands()
        );

    }

    /**
     */
    public function test_hasSolution()
    {
        $below = new BelowRule('field_name', 1);
        $equal = new EqualRule('field_name', 2);
        $above = new AboveRule('field_name', 3);

        $this->assertFalse(
            (new AndRule([$below, $equal]))->simplify()->hasSolution()
        );

        $this->assertFalse(
            (new AndRule([$equal, $above]))->simplify()->hasSolution()
        );

        $this->assertFalse(
            (new AndRule([$below, $above]))->simplify()->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([$below]))->simplify()->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([$equal]))->simplify()->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([$above]))->simplify()->hasSolution()
        );
    }

    /**
     */
    public function test_hasSolution_withNegations()
    {
        $below = new BelowRule('field_name', 1);
        $equal = new EqualRule('field_name', 2);
        $above = new AboveRule('field_name', 3);

        // $result =(new AndRule([$above, new NotRule($above)]))
            // ->removeNegations()
            // ->upLiftDisjunctions()
            // ->unifyOperands()
            // ->simplify()
            // ->dump(true)
            // ;
        // var_dump($result->simplify()->hasSolution());

        $this->assertFalse(
            (new AndRule([$above, new NotRule($above)]))->simplify()->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([new NotRule($above), new NotRule($above)]))->simplify()->hasSolution()
        );

        $this->assertFalse(
            (new AndRule([$below, new NotRule($below)]))->simplify()->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([new NotRule($below), new NotRule($below)]))->simplify()->hasSolution()
        );

        $this->assertFalse(
            (new AndRule([$equal, new NotRule($equal)]))->simplify()
                // ->dump()
                ->hasSolution()
        );

        $this->assertTrue(
            (new AndRule([new NotRule($equal), new NotRule($equal)]))->simplify()->hasSolution()
        );
    }

    /**/
}
