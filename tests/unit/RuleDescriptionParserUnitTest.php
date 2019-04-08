<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\Rule\EqualRule;
use JClaveau\LogicalFilter\RuleDescriptionParser;

class RuleDescriptionParserUnitTest extends \AbstractTest
{
    /**
     */
    public function test_addRule_with_bad_operation()
    {
        try {
            VisibilityViolator::callHiddenMethod(
                RuleDescriptionParser::class, 'addRule',
                [new EqualRule('field', 2), 'bad_operator', new EqualRule('field', 3)]
            );

            $this->assertTrue(
                false,
                "An exception claiming that an invalid operator is given "
                ."should have been thrown here"
            );
        }
        catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                "InvalidArgumentException throw: \$operation must be 'and' or 'or' instead of: 'bad_operator'",
                "InvalidArgumentException throw: ".$e->getMessage()
            );
        }
    }

    /**/
}
