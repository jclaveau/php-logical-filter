<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\NotRule;
use JClaveau\LogicalFilter\Rule\InRule;
use JClaveau\LogicalFilter\Rule\EqualRule;
use JClaveau\LogicalFilter\Rule\AboveRule;
use JClaveau\LogicalFilter\Rule\BelowRule;

require  __DIR__ . "/LogicalFilterTest_rules_manipulation_trait.php";
require  __DIR__ . "/simplification/LogicalFilterTest_rules_simplification_trait_leaf_rules.php";
require  __DIR__ . "/simplification/LogicalFilterTest_rules_simplification_trait_composit_rules.php";
require  __DIR__ . "/simplification/simplify_same_operands_trait.php";
require  __DIR__ . "/simplification/simplify_different_operands_trait.php";
require  __DIR__ . "/simplification/simplify_normalization_trait.php";
require  __DIR__ . "/simplification/simplify_force_logical_core_trait.php";
require  __DIR__ . "/LogicalFilterTest_collection_integration_trait.php";
require  __DIR__ . "/LogicalFilterTest_rules_descriptions_trait.php";

class LogicalFilterTest extends \AbstractTest
{
    use LogicalFilterTest_rules_descriptions;
    use LogicalFilterTest_rules_manipulation_trait;
    use LogicalFilterTest_rules_simplification_leaf_rules;
    use LogicalFilterTest_rules_simplification_composit_rules;
    use LogicalFilterTest_collection_integration_trait;
    use LogicalFilterTest_simplify_same_operands;
    use LogicalFilterTest_simplify_different_operands;
    use LogicalFilterTest_simplify_normalization;
    use LogicalFilterTest_simplify_force_logical_core;

    /**
     */
    public function test_getRules()
    {
        $filter = new LogicalFilter();
        $filter->and_('field', 'in', ['a', 'b', 'c']);

        $this->assertEquals(
            (new InRule('field', ['a', 'b', 'c']))->toArray(),
            $filter->getRules()->toArray()
        );
    }

    /**
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function test_jsonSerialize()
    {
        $this->assertEquals(
            '["or",["and",["field_5",">","a"],["field_5","<","a"]],["field_6","=","b"]]',
            json_encode(
                new LogicalFilter([
                    'or',
                    [
                        'and',
                        ['field_5', 'above', 'a'],
                        ['field_5', 'below', 'a'],
                    ],
                    ['field_6', 'equal', 'b'],
                ])
            )
        );
    }

    /**
     */
    public function test_copy()
    {
        $filter = new LogicalFilter([
            'or',
            [
                'and',
                ['field_5', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ],
            ['field_6', 'equal', 'b'],
        ]);

        $filter2 = $filter->copy();

        $this->assertEquals($filter, $filter2);

        $this->assertNotEquals(
            spl_object_hash($filter->getRules(false)),
            spl_object_hash($filter2->getRules(false))
        );

        // copy filter with no rule
        $filter = new LogicalFilter();
        $filter->copy();

        $this->assertNull( $filter->getRules() );
    }

    /**
     */
    public function test_saveAs()
    {
        $filter = new LogicalFilter([
            'or',
            ['field_6', 'equal', 'b'],
        ]);

        $returned_filter = $filter->saveAs( $filter2 );

        $this->assertSame($filter, $filter2);
        $this->assertSame($filter, $returned_filter);
    }

    /**
     */
    public function test_saveCopyAs()
    {
        $filter = new LogicalFilter([
            'or',
            ['field_6', 'equal', 'b'],
        ]);

        $returned_filter = $filter->saveCopyAs( $filter2 );

        $this->assertSame($filter, $returned_filter);

        $this->assertNotEquals(
            spl_object_hash($filter->getRules(false)),
            spl_object_hash($filter2->getRules(false))
        );

        $this->assertEquals(
            $filter->toArray(),
            $filter2->toArray()
        );
    }

    /**
     * @todo debug the runInseparateProcess of php to test the exit call.
     * @ runInSeparateProcess
     */
    public function test_dump_export()
    {
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'export'])
            ;
        $dump = ob_get_clean();

        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
                array (
                  0 => 'field_1',
                  1 => '=',
                  2 => 3,
                )

                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

        // instance debuging enabled
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'export', 'show_instance' => true])
            ;
        $dump = ob_get_clean();

        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
                array (
                  0 => 'field_1',
                  1 => 'JClaveau\\\\LogicalFilter\\\\Rule\\\\EqualRule:XX',
                  2 => 3,
                )

                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );
    }

    /**
     */
    public function test_dump_dump()
    {
        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'dump'])
            ;
        $dump = ob_get_clean();
        // echo $dump;
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
array(3) {
  [0]=>
  string(7) \"field_1\"
  [1]=>
  string(1) \"=\"
  [2]=>
  int(3)
}


                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

    }

    /**
     */
    public function test_dump_xdebug()
    {
        if ( ! function_exists('xdebug_is_enabled')) {
            $this->markTestSkipped();
        }
        if ( ! xdebug_is_enabled()) {
            $this->markTestSkipped();
        }

        ob_start();
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->dump(false, ['mode' => 'xdebug'])
            ;
        $dump = ob_get_clean();
        // echo $dump;
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
array(3) {
  [0] =>
  string(7) \"field_1\"
  [1] =>
  string(1) \"=\"
  [2] =>
  int(3)
}


                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );
    }

    /**
     */
    public function test_dump_string()
    {
        ob_start();
        $filter = (new LogicalFilter( ['and', ['field_1', '=', 3], ['field_2', '!=', null]] ))
            ->dump(false, ['mode' => 'string', 'indent_unit' => '  '])
            ;
        $dump = ob_get_clean();
        $this->assertEquals(
            str_replace('    ', '', "
                ". __FILE__ .":XX
['and',
  ['field_1', '=', 3],
  ['field_2', '!=', NULL],
]

                "
            ),
            preg_replace('/:\d+/', ':XX', $dump)
        );

        // exit once dumped
        // TODO this makes phpunit bug while echoing text before calling exit;
        // ob_start();
        // $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            // ->dump(true)
            // ;
            // echo 'plop';
            // exit;
        // $dump = ob_get_clean();
        // $this->assertEquals(
            // str_replace('    ', '', "
                // /home/jean/dev/mediabong/apps/php-logical-filter/tests/public api/LogicalFilterTest.php:XX
                // array (
                  // 0 => 'field_1',
                  // 1 => '=',
                  // 2 => 3,
                // )

                // "
            // ),
            // preg_replace('/:\d+/', ':XX', $dump)
        // );
    }

    /**
     */
    public function test_toString()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_1', '=', 3],
                    ['field_1', '!=', 100],
                    ['field_1', '>', 20],
                ],
                ['not',
                    ['field_2', '<', -5],
                ],
                ['field_1', 'regexp', "/^prefix-[^-]+-suffix$/"],
                ['field_3', 'in', [2, null]],
                ['field_4', '!in', [4, 12]],
                ['field_5', '<=', 3],
                ['field_5', '>=', 12],
                ['field_6', '><', [20, 30]],
                ['field_6', '=><', [20, 30]],
                ['field_6', '=><=', [20, 30]],
                ['field_6', '><=', [20, 30]],
                ['date', '>', new \DateTime("2018-07-19")],
                [key(), '=', 3],
                [value()->lazyMethodCall(), '=', 3],
            ]
        ))
        // ->dump(true)
        ;

        // This call is just meant to expose possible cache collision with toArray
        $filter->toArray();

        $this->assertEquals(
"['and',
    ['or',
        ['field_1', '=', 3],
        ['field_1', '!=', 100],
        ['field_1', '>', 20],
    ],
    ['not',
        ['field_2', '<', -5],
    ],
    ['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],
    ['field_3', 'in', [2, NULL]],
    ['field_4', '!in', [4, 12]],
    ['field_5', '<=', 3],
    ['field_5', '>=', 12],
    ['field_6', '><', [20, 30]],
    ['field_6', '=><', [20, 30]],
    ['field_6', '=><=', [20, 30]],
    ['field_6', '><=', [20, 30]],
    ['date', '>', DateTime::__set_state(array(
       'date' => '2018-07-19 00:00:00.000000',
       'timezone_type' => 3,
       'timezone' => 'UTC',
    ))],
    [(new JClaveau\LogicalFilter\FilteredKey), '=', 3],
    [(new JClaveau\LogicalFilter\FilteredValue)->lazyMethodCall(), '=', 3],
]",
            $filter->toString(['indent_unit' => "    "])
        );

        // toArray must be iso to the provided descrition
        // echo $filter->toString()."\n\n";
        $this->assertEquals(
"['and',['or',['field_1', '=', 3],['field_1', '!=', 100],['field_1', '>', 20],],['not', ['field_2', '<', -5],],['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],['field_3', 'in', [2, NULL]],['field_4', '!in', [4, 12]],['field_5', '<=', 3],['field_5', '>=', 12],['field_6', '><', [20, 30]],['field_6', '=><', [20, 30]],['field_6', '=><=', [20, 30]],['field_6', '><=', [20, 30]],['date', '>', DateTime::__set_state(array(
   'date' => '2018-07-19 00:00:00.000000',
   'timezone_type' => 3,
   'timezone' => 'UTC',
))],[(new JClaveau\LogicalFilter\FilteredKey), '=', 3],[(new JClaveau\LogicalFilter\FilteredValue)->lazyMethodCall(), '=', 3],]",
            $filter->toString()
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
"['and',['or',['field_1', '=', 3],['field_1', '!=', 100],['field_1', '>', 20],],['not', ['field_2', '<', -5],],['field_1', 'regexp', '/^prefix-[^-]+-suffix$/'],['field_3', 'in', [2, NULL]],['field_4', '!in', [4, 12]],['field_5', '<=', 3],['field_5', '>=', 12],['field_6', '><', [20, 30]],['field_6', '=><', [20, 30]],['field_6', '=><=', [20, 30]],['field_6', '><=', [20, 30]],['date', '>', DateTime::__set_state(array(
   'date' => '2018-07-19 00:00:00.000000',
   'timezone_type' => 3,
   'timezone' => 'UTC',
))],[(new JClaveau\LogicalFilter\FilteredKey), '=', 3],[(new JClaveau\LogicalFilter\FilteredValue)->lazyMethodCall(), '=', 3],]",
            $filter . ''
        );
    }

    /**
     */
    public function test_invoke()
    {
        $row_to_match = [
            'field_1' => 8,
            'field_2' => 3,
        ];

        $row_to_mismatch = [
            'field_1' => 12,
            'field_2' => 4,
        ];

        $filter = (new LogicalFilter(
            ['field_2', '!=', 4]
        ))
        // ->dump(true)
        ;

        $this->assertTrue(
            $filter( $row_to_match )
                // ->dump()
                ->hasSolution()
        );

        $this->assertFalse(
            $filter( $row_to_mismatch )
        );

    }

    /**
     */
    public function test_array_filter()
    {
        $array = [
            [
                'field_1' => 8,
                'field_2' => 3,
            ],
            [
                'field_1' => 12,
                'field_2' => 4,
            ],
        ];

        $this->assertEquals(
            [
                [
                    'field_1' => 8,
                    'field_2' => 3,
                ],
            ],
            array_filter( $array, new LogicalFilter(
                ['field_2', '!=', 4]
            ))
        );
    }

    /**
     */
    public function test_validates()
    {
        $filter = (new LogicalFilter(
            [
                [value(), '=', 4],
                'or',
                [key(), '=', 'index1'],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertTrue(
            $filter->validates( 3, 'index1' )
                // ->dump(!true)
                ->hasSolution()
        );

        $this->assertTrue(
            $filter->validates( 4, 'invalid_key' )
                // ->dump(!true)
                ->hasSolution()
        );

        $this->assertFalse(
            $filter->validates( 5, 'invalid_key' )
        );
    }

    /**
     */
    public function test_getSemanticId()
    {
        $filter = new LogicalFilter;

        $filter
            ->and_('field', 'in', [])
            ->and_('field2', '=', 'dfghjkl')
            ;

        $this->assertEquals(
            '460c39ed20e85bc0dcafc28b5e4e5d4d-511711247b38d5ed3ee96dee4d3bf89a',
            $filter
                // ->dump()
                ->getSemanticId()
        );
    }

    /**/
}
