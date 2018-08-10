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
            ['or',
                ['field', '>', 4],
                ['field', '=', 4],
            ],
            $rule
                ->simplify()
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
            $this->assertEquals(
                "Setting invalid operand for ['field', '>=', 4]: ['and']",
                $e->getMessage()
            );
        }

        try {
            $rule->setOperands([
                new EqualRule('field', 4),
                new AboveRule('field', 3),
            ]);
            $this->markTestInvalid("An exception should have been thrown here");
        }
        catch (\LogicException $e) {
            $this->assertEquals(
                "Trying to set different values for ['field', '>=', 4]: array (\n  'equal' => 4,\n  'above' => 3,\n)",
                $e->getMessage()
            );
        }

        try {
            $rule->setOperands([]);
            $this->markTestInvalid("An exception should have been thrown here");
        }
        catch (\LogicException $e) {
            $this->assertEquals(
                "Trying to set null values for ['field', '>=', 4]",
                $e->getMessage()
            );
        }

    }

    /**/
}
