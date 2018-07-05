<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class FiltererTest extends \AbstractTest
{
    /**
     */
    public function test_filterer_php_native()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_4', '!=', null],
            ['non_existing_field', '=', 8],
        ]);

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
        ];

        $filterer = new PhpFilterer();

        $filtered_data = $filterer->apply( $filter, $data_to_filter );

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

    /**
     */
    public function test_filterer_customizable()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_4', '!=', null],
        ]);

        $data_to_filter = [
            [
                'name'    => '1: valid row',
                'field_1' => 2,
                'field_2' => 12,
                // no filed 3 <=> null
                'field_4' => 12,
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
        ];

        $filterer = new CustomizableFilterer(
            function ($field, $operator, $value, $row, $allOperandsByField) {

                if ($operator === '=') {
                    if ($value === null) {
                        return !isset($row[$field]);
                    }
                    else {
                        return $row[$field] == $value;
                    }
                }
                elseif ($operator === '<') {
                    return $row[$field] < $value;
                }
                elseif ($operator === '>') {
                    return $row[$field] > $value;
                }
                elseif ($operator === '!=') {
                    if ($value === null) {
                        return isset($row[$field]);
                    }
                    else {
                        throw new \InvalidArgumentException(
                            "This case shouldn't occure with teh current simplification strategy"
                        );
                        // return $row[$field] == $operand->getValue();
                    }
                }
            }
        );

        $filtered_data = $filterer->apply( $filter, $data_to_filter );

        $this->assertEquals(
            [
                0 => [
                'name'    => '1: valid row',
                    'field_1' => 2,
                    'field_2' => 12,
                    'field_4' => 12,
                ],
                3 => [
                'name'    => '4: valid row',
                    'field_1' => 2,
                    'field_2' => -12,
                    'field_3' => null,
                    'field_4' => 0,
                ],
            ],
            $filtered_data
        );
    }

    /**
     */
    public function test_filterer_rule()
    {
        $filter_to_filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_2', '!=', null],
        ]);

        $filtered_rules = (new RuleFilterer)
        ->apply(
            new LogicalFilter([
                'and',
                ['field',    '=',  'field_2'],
                ['operator', '!=', '!='],
            ]),
            $filter_to_filter->getRules()
        )
        // ->dump()
        ;

        $this->assertEquals(
            ['and',
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
            ],
            $filtered_rules->toArray()
        );
    }

    /**/
}
