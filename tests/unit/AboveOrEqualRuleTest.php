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
                ->dump()
                ->toArray()
        );
    }

    /**/
}
