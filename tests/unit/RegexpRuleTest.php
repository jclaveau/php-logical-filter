<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class RegexpRuleTest extends \AbstractTest
{
    /**
     */
    public function test_php2mariadbPCRE()
    {
        $this->assertEquals("(?mi)^l*a([la])+$", RegexpRule::php2mariadbPCRE("/^l*a([la])+$/mi"));

        $this->assertEquals("(?mi)^l*a([la])+$", RegexpRule::php2mariadbPCRE("#^l*a([la])+$#mi"));

        $this->assertEquals("^l*a([la])+$", RegexpRule::php2mariadbPCRE("#^l*a([la])+$#"));
    }

    /**/
}
