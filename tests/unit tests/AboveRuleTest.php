<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class AboveRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_hasSolution()
    {
        $this->assertFalse( (new AboveRule('field_name', NAN))->hasSolution() );

        $this->assertFalse( (new AboveRule('field_name', INF))->hasSolution() );

        $this->assertTrue( (new AboveRule('field_name', -INF))->hasSolution() );

        $this->assertTrue( (new AboveRule('field_name', 3))->hasSolution() );
    }

    /**/
}
