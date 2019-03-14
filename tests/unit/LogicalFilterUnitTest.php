<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\Rule\EqualRule;

class LogicalFilterUnitTest extends \AbstractTest
{
    /**
     */
    public function test_addRule_with_bad_operation()
    {
        $filter = new LogicalFilter(['field_1', '=', 3]);

        try {
            VisibilityViolator::callHiddenMethod(
                $filter, 'addRule', [new EqualRule('field', 2), 'bad_operator']
            );

            $this->assertTrue(
                false,
                "An exception claiming that an invaid operator is given "
                ."should have been thrown here"
            );
        }
        catch (\InvalidArgumentException $e) {
            $this->assertTrue(true, "InvalidArgumentException throw: ".$e->getMessage());
        }
    }


    /**/
}
