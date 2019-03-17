<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class PhpFiltererTest extends \AbstractTest
{
    /**
     */
    public function test_filtering_cases()
    {
        $data_to_filter = [
            [
                'name'    => '1: valid row',
                'field_1' => 2,
                'field_2' => 12,
                // no filed 3 <=> null
                'field_4' => 12,
                'non_existing_field' => 8,
            ],
            [
                'name'    => '2: field_1 invalid',
                'field_1' => 3,
                'field_2' => 12,
                'field_4' => "aze",
            ],
            [
                'name'    => '3: field_2 invalid',
                'field_1' => 2,
                'field_2' => 2,
                'field_4' => "aze",
            ],
            [
                'name'    => '4: valid row',
                'field_1' => 2,
                'field_2' => -12,
                'field_3' => null,
                'field_4' => 0,
                'non_existing_field' => 8,
            ],
            [
                'name'    => '5: field_2 invalid',
                'field_1' => 3,
                'field_2' => 2,
                'field_3' => null,
                'field_4' => 'abc',
            ],
            [
                'name'    => '6: field_3 invalid && filed_4 valid',
                'field_1' => 2,
                'field_2' => -12,
                'field_3' => -12,
                'field_4' => 0, // != null
            ],
            [
                'name'    => '7: field_4 invalid',
                'field_1' => 2,
                'field_2' => -12, // invalid
                'field_4' => null,
            ],
            [
                'name'    => '7: field_6 invalid',
                'field_1' => 2,
                'field_2' => -12, // invalid
                'field_4' => 0,
                'field_6' => 9,
            ],
        ];

        $filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_4', '!=', null],
                ['non_existing_field', '=', 8],
                ['field_6', '!in', [8, 9, 10]], // add != null rule to check if the field exists
                ['field_4', '<=', 16],
                ['field_4', '>=', -5],
            ]
        );

        $filterer = new PhpFilterer();

        $filtered_data = $filterer->apply($filter, $data_to_filter);

        $this->assertEquals(
            [
                0 => [
                    'name'    => '1: valid row',
                    'field_1' => 2,
                    'field_2' => 12,
                    'field_4' => 12,
                    'non_existing_field' => 8,
                ],
                3 => [
                    'name'    => '4: valid row',
                    'field_1' => 2,
                    'field_2' => -12,
                    'field_3' => null,
                    'field_4' => 0,
                    'non_existing_field' => 8,
                ],
            ],
            $filtered_data
        );
    }

    /**/
}
