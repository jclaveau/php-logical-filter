<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class AboveOrEqualRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_setOperandsOrReplaceByOperation()
    {
        $rule = new AboveOrEqualRule('field', 4);
        $operands = $rule->getOperands();
        $operands[0] = new AboveRule('field_2', 3);
        $rule = $rule->setOperandsOrReplaceByOperation($operands);

        $this->assertEquals(
            ['or',
                ['field_2', '>', 3],
                ['field', '=', 4],
            ],
            $rule
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify()
    {
        $rule = new AboveOrEqualRule('field', 4);

        $this->assertEquals(
            ['field', '>=', 4],
            $rule
                ->simplify()
                // ->dump()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field', '>', 4],
                ['field', '=', 4],
            ],
            $rule
                ->simplify(['above_or_equal.normalization' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_setOperands_working_in_any_order()
    {
        $rule = new AboveOrEqualRule('field', 4);

        $rule->setOperands([
            new AboveRule('field', 3),
            new EqualRule('field', 3),
        ]);

        $this->assertEquals(
            ['field', '>=', 3],
            $rule
                // ->dump()
                ->toArray()
        );

        $rule->setOperands([
            new EqualRule('field', 2),
            new AboveRule('field', 2),
        ]);

        $this->assertEquals(
            ['field', '>=', 2],
            $rule
                // ->dump()
                ->toArray()
        );

    }

    /**
     */
    public function test_setOperands_exceptions()
    {
        $rule = new AboveOrEqualRule('field', 4);

        try {
            $rule->setOperands([
                new AndRule([])
            ]);
            $this->markTestInvalid("An exception should have been thrown here");
        }
        catch (\LogicException $e) {
            $this->assertEquals(0, strpos(
                $e->getMessage(),
                "Setting invalid operand for ['field', '>=', 4]: ['and']"
            ) );
        }

        try {
            $rule->setOperands([
                new EqualRule('field', 4),
                new AboveRule('field', 3),
            ]);
            $this->markTestInvalid("An exception should have been thrown here");
        }
        catch (\LogicException $e) {
            $this->assertEquals(0, strpos(
                $e->getMessage(),
                "Operands must be an array of two rules like (field > minimum || field = minimum) instead of:"
            ) );
        }

        try {
            $rule->setOperands([]);
            $this->markTestInvalid("An exception should have been thrown here");
        }
        catch (\LogicException $e) {
            $this->assertEquals(0, strpos(
                $e->getMessage(),
                "Operands must be an array of two rules like (field > minimum || field = minimum) instead of:"
            ) );
        }

    }

    /**
     */
    public function test_setField_with_cache()
    {
        $rule = new AboveOrEqualRule('field', 3);
        // set the cache
        $rule->toArray();

        $rule->setField('field_2');

        $this->assertEquals(
            ['field_2', '>=', 3],
            $rule->toArray()
        );
    }

    /**/
}
