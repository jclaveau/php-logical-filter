<?php
namespace JClaveau\CustomFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

use JClaveau\CustomFilter\Rule\OrRule;
use JClaveau\CustomFilter\Rule\AndRule;
use JClaveau\CustomFilter\Rule\NotRule;
use JClaveau\CustomFilter\Rule\InRule;
use JClaveau\CustomFilter\Rule\EqualRule;
use JClaveau\CustomFilter\Rule\AboveRule;
use JClaveau\CustomFilter\Rule\BelowRule;

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
            new AndRule([
                new InRule('field', ['a', 'b', 'c']),
                // new NotInRule(['a', 'b', 'c']),
                new AboveRule('field', 3),
                new BelowRule('field', 5)
            ]),
            $rules
        );
    }

    /**
     */
    public function test_getRules()
    {
        $filter = new Filter();

        $filter->addSimpleRule('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            new AndRule([
                new InRule('field', ['a', 'b', 'c'])
            ]),
            $filter->getRules()
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
            new AndRule([
                new OrRule([
                    new InRule('field', ['a', 'b', 'c']),
                    new EqualRule('field', 'e')
                ]),
            ]),
            $filter->getRules()
        );
    }

    /**
     */
    public function test_addRule_with_nested_operations()
    {
        $filter = new Filter();

        $filter->addCompositeRule([
            ['field', 'in', ['a', 'b', 'c']],
            'or',
            [
                ['field', 'in', ['d', 'e']],
                'and',
                [
                    ['field_2', 'above', 3],
                    'or',
                    ['field_3', 'below', -2],
                ],
            ],
        ]);

        $this->assertEquals(
            new AndRule([
                new OrRule([
                    new InRule('field', ['a', 'b', 'c']),
                    new AndRule([
                        new InRule('field', ['d', 'e']),
                        new OrRule([
                            new AboveRule('field_2', 3),
                            new BelowRule('field_3', -2),
                        ]),
                    ]),
                ]),
            ]),
            $filter->getRules()
        );
    }

    /**
     */
    public function test_addRule_with_different_operators()
    {
        $filter = new Filter();

        // exception if different operators in the same operation
        try {
            $filter->addCompositeRule([
                ['field', 'in', ['a', 'b', 'c']],
                'or',
                [
                    ['field', 'in', ['d', 'e']],
                    'and',
                    [
                        ['field_2', 'above', 3],
                        'or',
                        ['field_3', 'below', -2],
                        'and',
                        ['field_3', 'equal', 0],
                    ],
                ],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown for different operators in one operation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Mixing different operations in the same rule level not implemented:/",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     */
    public function test_addRule_without_operator()
    {
        $filter = new Filter();

        // exception if no operator in an operation
        try {
            $filter->addCompositeRule([
                ['field_2', 'above', 3],
                ['field_3', 'below', -2],
                ['field_3', 'equal', 0],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown while operator is missing in an operation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Please provide an operator for the operation: /",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     */
    public function test_addRule_with_negation()
    {
        $filter = new Filter();

        $filter->addCompositeRule([
            'not',
            ['field_2', 'above', 3],
        ]);

        $this->assertEquals(
            new AndRule([
                new NotRule(
                    new AboveRule('field_2', 3)
                )
            ]),
            $filter->getRules()
        );

        // not with too much operands
        try {
            $filter->addCompositeRule([
                'not',
                ['field_2', 'above', 3],
                ['field_2', 'equal', 5],
            ]);

            $this->assertTrue(
                false,
                'No exception thrown if two operands for a negation'
            );
        }
        catch (\InvalidArgumentException $e) {

            $this->assertTrue(
                (bool) preg_match(
                    "/^Negations can have only one operand: /",
                    $e->getMessage()
                )
            );
            return;
        }
    }

    /**
     * @todo complexe with negations of Operations rules having more than
     * 2 operands.
     */
    public function test_removeNegations()
    {
        // simple
        $filter = new Filter();

        $filter->addCompositeRule([
            'not',
            ['field_2', 'above', 3],
        ]);

        $filter->removeNegations();

        $this->assertEquals(
            new AndRule([
                new OrRule([
                    new BelowRule('field_2', 3),
                    new EqualRule('field_2', 3),
                ])
            ]),
            $filter->getRules()
        );

        // complex
        $filter = new Filter();

        $filter->addCompositeRule([
            'or',
            ['field_1', 'below', 3],
            ['not', ['field_2', 'above', 3]],
            ['not', ['field_3', 'in', [7, 11, 13]]],
            ['not',
                [
                    'or',
                    ['field_4', 'below', 2],
                    ['field_5', 'in', ['a', 'b', 'c']],
                ],
            ],
        ]);

        $filter->removeNegations();

        $filter2 = new Filter;

        $filter2->addCompositeRule([
            'or',
            ['field_1', 'below', 3],
            // ['not', ['field_2', 'above', 3]],
            [
                'or',
                ['field_2', 'below', 3],
                ['field_2', 'equal', 3],
            ],
            // ['not', ['field_3', 'in', [7, 11, 13]]],
            [
                'and',
                [
                    'or',
                    ['field_3', 'above', 7],
                    ['field_3', 'below', 7],
                ],
                [
                    'or',
                    ['field_3', 'above', 11],
                    ['field_3', 'below', 11],
                ],
                [
                    'or',
                    ['field_3', 'above', 13],
                    ['field_3', 'below', 13],
                ],
            ],
            // ['not',
                // [
                    // 'or',
                    // ['field_4', 'below', 2],
                    // ['field_5', 'in', ['a', 'b', 'c']],
                // ],
            // ],
            [
                'and',
                [
                    'or',
                    ['field_4', 'above', 2],
                    ['field_4', 'equal', 2],
                ],
                [
                    'and',
                    [
                        'or',
                        ['field_5', 'above', 'a'],
                        ['field_5', 'below', 'a'],
                    ],
                    [
                        'or',
                        ['field_5', 'above', 'b'],
                        ['field_5', 'below', 'b'],
                    ],
                    [
                        'or',
                        ['field_5', 'above', 'c'],
                        ['field_5', 'below', 'c'],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            $filter2->getRules(),
            $filter->getRules()
        );
    }

    /**/
}
