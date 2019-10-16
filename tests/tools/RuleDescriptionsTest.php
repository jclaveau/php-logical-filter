<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\VisibilityViolator\VisibilityViolator;

class RuleDescriptionsTest extends \AbstractTest
{
    /**
     */
    public function test_listStatementRuleCombinations()
    {
        $combinations = RuleDescriptions::listStatementRuleCombinations();

        // var_export($combinations);

        $this->assertEquals(
            array (
              0 => '= vs =',
              1 => '< vs =',
              2 => '< vs <',
              3 => '> vs =',
              4 => '> vs <',
              5 => '> vs >',
              6 => '!= vs =',
              7 => '!= vs <',
              8 => '!= vs >',
              9 => '!= vs !=',
              10 => 'in vs =',
              11 => 'in vs <',
              12 => 'in vs >',
              13 => 'in vs !=',
              14 => 'in vs in',
              15 => '!in vs =',
              16 => '!in vs <',
              17 => '!in vs >',
              18 => '!in vs !=',
              19 => '!in vs in',
              20 => '!in vs !in',
              21 => '>= vs =',
              22 => '>= vs <',
              23 => '>= vs >',
              24 => '>= vs !=',
              25 => '>= vs in',
              26 => '>= vs !in',
              27 => '>= vs >=',
              28 => '<= vs =',
              29 => '<= vs <',
              30 => '<= vs >',
              31 => '<= vs !=',
              32 => '<= vs in',
              33 => '<= vs !in',
              34 => '<= vs >=',
              35 => '<= vs <=',
              36 => '>< vs =',
              37 => '>< vs <',
              38 => '>< vs >',
              39 => '>< vs !=',
              40 => '>< vs in',
              41 => '>< vs !in',
              42 => '>< vs >=',
              43 => '>< vs <=',
              44 => '>< vs ><',
              45 => '=>< vs =',
              46 => '=>< vs <',
              47 => '=>< vs >',
              48 => '=>< vs !=',
              49 => '=>< vs in',
              50 => '=>< vs !in',
              51 => '=>< vs >=',
              52 => '=>< vs <=',
              53 => '=>< vs ><',
              54 => '=>< vs =><',
              55 => '><= vs =',
              56 => '><= vs <',
              57 => '><= vs >',
              58 => '><= vs !=',
              59 => '><= vs in',
              60 => '><= vs !in',
              61 => '><= vs >=',
              62 => '><= vs <=',
              63 => '><= vs ><',
              64 => '><= vs =><',
              65 => '><= vs ><=',
              66 => '=><= vs =',
              67 => '=><= vs <',
              68 => '=><= vs >',
              69 => '=><= vs !=',
              70 => '=><= vs in',
              71 => '=><= vs !in',
              72 => '=><= vs >=',
              73 => '=><= vs <=',
              74 => '=><= vs ><',
              75 => '=><= vs =><',
              76 => '=><= vs ><=',
              77 => '=><= vs =><=',
              78 => 'regexp vs =',
              79 => 'regexp vs <',
              80 => 'regexp vs >',
              81 => 'regexp vs !=',
              82 => 'regexp vs in',
              83 => 'regexp vs !in',
              84 => 'regexp vs >=',
              85 => 'regexp vs <=',
              86 => 'regexp vs ><',
              87 => 'regexp vs =><',
              88 => 'regexp vs ><=',
              89 => 'regexp vs =><=',
              90 => 'regexp vs regexp',
            ),
            $combinations
        );
    }

    /**/
}
