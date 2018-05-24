<?php
namespace JClaveau\LogicalFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class BelowRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_hasSolution()
    {
        $this->assertFalse( (new BelowRule('field_name', NAN))->hasSolution() );

        $this->assertFalse( (new BelowRule('field_name', -INF))->hasSolution() );

        $this->assertTrue( (new BelowRule('field_name', INF))->hasSolution() );

        $this->assertTrue( (new BelowRule('field_name', 3))->hasSolution() );
    }

    /**/
}
