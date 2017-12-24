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
    public function test_addSimpleRule()
    {
        $filter = new Filter();

        $filter->addSimpleRule('field', 'in', ['a', 'b', 'c']);
        // $filter->addRule('field', 'not_in', ['a', 'b', 'c']);
        $filter->addSimpleRule('field', 'above', 3);
        $filter->addSimpleRule('field', 'below', 5);

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
    public function test_addOrRule()
    {
        $filter = new Filter();

        $filter->addCompositeRule([
            ['field', 'in', ['a', 'b', 'c']],
            'or',
            ['field', 'equal', 'e']
        ]);

        $this->assertEquals(
            new Rule\AndRule([
                new Rule\OrRule([
                    new Rule\InRule('field', ['a', 'b', 'c']),
                    new Rule\EqualRule('field', 'e')
                ]),
            ]),
            $filter->getRules()
        );
    }

    /**
     */
    public function test_getRules()
    {
        $filter = new Filter();

        $filter->addSimpleRule('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            new Rule\AndRule([
                new Rule\InRule('field', ['a', 'b', 'c'])
            ]),
            $filter->getRules()
        );
    }

    /**
     * @todo
     */
    public function test_removeNegations()
    {
    }

    /**/
}
