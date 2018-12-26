<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class BelowOrEqualRuleTest extends \AbstractTest
{
    /**
     */
    public function test_exception_thrown_because_of_inconsistency()
    {
        $rule = new BelowOrEqualRule('field', 4);
        $operands = $rule->getOperands();
        $operands[0] = new BelowRule('field_2', 3);

        try {
            $rule->setOperands($operands);
            $this->assertFalse( $rule->getMaximum() );
            $this->assertTrue(
                false,
                "An exception explaining that the two operands are do not mean "
                ." 'below or equal' should have been thrown"
            );
        }
        catch (\Exception $e) {
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }

        $this->assertEquals(
            ['field', '<=', 4],
            $rule
                // ->dump()
                ->toArray()
        );
    }

    /**/
}
