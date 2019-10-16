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

        $this->assertEquals(
            array (
              0 => '< vs =',
              1 => '> vs =',
              2 => '> vs <',
              3 => '!= vs =',
              4 => '!= vs <',
              5 => '!= vs >',
              6 => 'in vs =',
              7 => 'in vs <',
              8 => 'in vs >',
              9 => 'in vs !=',
              10 => '!in vs =',
              11 => '!in vs <',
              12 => '!in vs >',
              13 => '!in vs !=',
              14 => '!in vs in',
              15 => '>= vs =',
              16 => '>= vs <',
              17 => '>= vs >',
              18 => '>= vs !=',
              19 => '>= vs in',
              20 => '>= vs !in',
              21 => '<= vs =',
              22 => '<= vs <',
              23 => '<= vs >',
              24 => '<= vs !=',
              25 => '<= vs in',
              26 => '<= vs !in',
              27 => '<= vs >=',
              28 => '>< vs =',
              29 => '>< vs <',
              30 => '>< vs >',
              31 => '>< vs !=',
              32 => '>< vs in',
              33 => '>< vs !in',
              34 => '>< vs >=',
              35 => '>< vs <=',
              36 => '=>< vs =',
              37 => '=>< vs <',
              38 => '=>< vs >',
              39 => '=>< vs !=',
              40 => '=>< vs in',
              41 => '=>< vs !in',
              42 => '=>< vs >=',
              43 => '=>< vs <=',
              44 => '=>< vs ><',
              45 => '><= vs =',
              46 => '><= vs <',
              47 => '><= vs >',
              48 => '><= vs !=',
              49 => '><= vs in',
              50 => '><= vs !in',
              51 => '><= vs >=',
              52 => '><= vs <=',
              53 => '><= vs ><',
              54 => '><= vs =><',
              55 => '=><= vs =',
              56 => '=><= vs <',
              57 => '=><= vs >',
              58 => '=><= vs !=',
              59 => '=><= vs in',
              60 => '=><= vs !in',
              61 => '=><= vs >=',
              62 => '=><= vs <=',
              63 => '=><= vs ><',
              64 => '=><= vs =><',
              65 => '=><= vs ><=',
              66 => 'regexp vs =',
              67 => 'regexp vs <',
              68 => 'regexp vs >',
              69 => 'regexp vs !=',
              70 => 'regexp vs in',
              71 => 'regexp vs !in',
              72 => 'regexp vs >=',
              73 => 'regexp vs <=',
              74 => 'regexp vs ><',
              75 => 'regexp vs =><',
              76 => 'regexp vs ><=',
              77 => 'regexp vs =><=',
            ),
            $combinations
        );
    }

    /**/
}
