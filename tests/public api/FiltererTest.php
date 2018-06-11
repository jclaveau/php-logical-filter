<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class FiltererTest extends \PHPUnit_Framework_TestCase
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
            ]
        ]);

        $data_to_filter = [
            [
                'name'    => 'valid row 1',
                'field_1' => 2,
                'field_2' => 12,
            ],
            [
                'name'    => 'invalid row 1',
                'field_1' => 3,
                'field_2' => 12,
            ],
            [
                'name'    => 'invalid row 2',
                'field_1' => 2,
                'field_2' => 2,
            ],
            [
                'name'    => 'valid row 2',
                'field_1' => 2,
                'field_2' => -12,
            ],
            [
                'name'    => 'invalid row 3',
                'field_1' => 3,
                'field_2' => 2,
            ],
        ];

        $filterer = new PhpFilterer();

        $filtered_data = $filterer->apply( $filter, $data_to_filter );

        $this->assertEquals(
            [
                0 => [
                    'name'    => 'valid row 1',
                    'field_1' => 2,
                    'field_2' => 12,
                ],
                3 => [
                    'name'    => 'valid row 2',
                    'field_1' => 2,
                    'field_2' => -12,
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
            ]
        ]);

        $data_to_filter = [
            [
                'name'    => 'valid row 1',
                'field_1' => 2,
                'field_2' => 12,
            ],
            [
                'name'    => 'invalid row 1',
                'field_1' => 3,
                'field_2' => 12,
            ],
            [
                'name'    => 'invalid row 2',
                'field_1' => 2,
                'field_2' => 2,
            ],
            [
                'name'    => 'valid row 2',
                'field_1' => 2,
                'field_2' => -12,
            ],
            [
                'name'    => 'invalid row 3',
                'field_1' => 3,
                'field_2' => 2,
            ],
        ];

        $filterer = new CustomizableFilterer(
            function ($field, $operator, $value, $row, $allOperandsByField) {

                if ($operator == '=') {
                    if ($value === null) {
                        return !isset($row[$field]);
                    }
                    else {
                        return $row[$field] == $value;
                    }
                }
                elseif ($operator == '<') {
                    return $row[$field] < $value;
                }
                elseif ($operator == '>') {
                    return $row[$field] > $value;
                }
                elseif ($operator == '!=') {
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
                    'name'    => 'valid row 1',
                    'field_1' => 2,
                    'field_2' => 12,
                ],
                3 => [
                    'name'    => 'valid row 2',
                    'field_1' => 2,
                    'field_2' => -12,
                ],
            ],
            $filtered_data
        );
    }

    /**/
}
