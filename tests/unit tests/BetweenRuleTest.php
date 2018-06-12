<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class BetweenRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_hasSolution()
    {
        $rule = new BetweenRule('field', [4, 5]);
        $this->assertTrue( $rule->hasSolution() );

        $rule = new BetweenRule('field', [5, 4]);
        $this->assertFalse( $rule->hasSolution() );

        $rule = new BetweenRule('field', [5, 5]);
        $this->assertFalse( $rule->hasSolution() );
    }

    /**
     */
    public function test_getField()
    {
        $rule = new BetweenRule('field', [4, 6]);

        $operands = $rule->getOperands();
        $operands[0] = new BelowRule('field_2', 3);
        $rule->setOperands($operands);

        try {
            $this->assertFalse( $rule->getField() );
            $this->assertTrue(
                false,
                "An exception explaining that the two operands have different fields "
            );
        }
        catch (\Exception $e) {
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**/
}
