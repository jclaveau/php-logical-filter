<?php
namespace JClaveau\CustomFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

class CustomFilterTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        //
        // ini_set('xdebug.max_nesting_level', 10000);
    }

    /**
     */
    public function test_addRule()
    {
        $filter = new Filter();

        $filter->addRule('field', 'in', ['a', 'b', 'c']);
        // $filter->addRule('field', 'not_in', ['a', 'b', 'c']);
        $filter->addRule('field', 'above', 3);
        $filter->addRule('field', 'below', 5);

        $rules = VisibilityViolator::getHiddenProperty(
            $filter,
            'rules'
        );

        $this->assertEquals(
            new Rule\AndRule([
                new Rule\InRule('field', ['a', 'b', 'c']),
                // new NotInRule(['a', 'b', 'c']),
                new Rule\AboveRule('field', 3),
                new Rule\BelowRule('field', 5)
            ]),
            $rules
        );
    }

    /**
     */
    public function test_getRules()
    {
        $filter = new Filter();

        $filter->addRule('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            new Rule\AndRule([
                new Rule\InRule('field', ['a', 'b', 'c'])
            ]),
            $filter->getRules()
        );
    }

    /**/
}
