<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class NotInRuleTest extends \AbstractTest
{
    /**
     * /
    public function test_simplify()
    {
        $rule = new NotInRule('field', [4, 5, 6]);
        // $rule->addPossibilities([7, 8, new EqualRule('field', 9)]);

        (new AndRule([$rule]))
        // ->dump(true)
        ->simplify([
            'stop_after' => 'remove_negations'
        ])
        ->dump(true)
        ;

        // $this->assertEquals(
            // [4, 5, 6, 7, 8, 9],
            // $rule->getPossibilities()
        // );

    }

    /**
     * /
    public function test_getField()
    {
        $rule = new InRule('field', [4, 5, 6]);

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
