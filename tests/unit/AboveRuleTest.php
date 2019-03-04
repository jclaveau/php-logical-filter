<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class AboveRuleTest extends \AbstractTest
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

    /**
     */
    public function test_toBool()
    {
        $this->assertFalse( (new AboveRule('field_name', NAN))->toBool() );
        $this->assertFalse( (new AboveRule('field_name', INF))->toBool() );
        $this->assertTrue( (new AboveRule('field_name', -INF))->toBool() );
        $this->assertTrue( (new AboveRule('field_name', 3))->toBool() );
    }

    /**/
}
