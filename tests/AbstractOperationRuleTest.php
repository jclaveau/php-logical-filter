<?php
namespace JClaveau\CustomFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class AbstractOperationRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_simplify_oneoperand()
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

    /**/
}
