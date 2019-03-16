<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;

class NotInRuleTest extends \AbstractTest
{
    /**
     */
    public function test_getField()
    {
        $rule = new NotInRule('field', [4, 5, 6]);
        $this->assertEquals('field', $rule->getField());
    }

    /**
     */
    public function test_getField_throwing_exception()
    {
        $rule = new NotInRule('field', [4, 5, 6]);

        $rule->setOperands([]);

        try {
            $rule->getField();
            $this->assertTrue(
                false,
                "An exception must have been thrown here"
            );
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "Trying to get the field of an InRule negation missing its operand",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_setField_working()
    {
        $rule = new NotInRule('field', [4, 5, 6]);
        $rule->setField('new_field');

        $this->assertEquals(
            ['new_field', '!in', [4, 5, 6]],
            $rule->toArray()
        );
    }

    /**
     */
    public function test_setPossibilities()
    {
        $rule = new NotInRule('field', [4, 5, 6]);
        $rule->setPossibilities([1, 2]);

        $this->assertEquals(
            ['field', '!in', [1, 2]],
            $rule->toArray()
        );
    }

    /**
     */
    public function test_setPossibilities_collection()
    {
        $rule = new NotInRule('field', [4, 5, 6]);
        $rule->setPossibilities(\DusanKasan\Knapsack\Collection::from([1, 2]));

        $this->assertEquals(
            ['field', '!in', [1, 2]],
            $rule->toArray()
        );
    }

    /**
     */
    public function test_setField_throwing_exception()
    {
        $rule = new NotInRule('field', [4, 5, 6]);

        $rule->setOperands([]);

        try {
            $rule->setField('new_field');
            $this->assertTrue(
                false,
                "An exception must have been thrown here"
            );
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "Trying to set the field of an InRule negation missing its operand",
                $e->getMessage()
            );
        }
    }

    /**/
}
