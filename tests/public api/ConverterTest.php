<?php
namespace JClaveau\LogicalFilter\Converter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_convert_to_mysql_with_customizable_converter()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ]
        ]);

        $mysql_where_part = "WHERE 1 ";

        $root_or_mysql = [];

        $converter = new CustomizableMinimalConverter(
            function () use (&$root_or_mysql) {
                $root_or_mysql[] = [];
            },
            // $this->onPossibleAnd($field, $operator, $operandsByOperator[0], $operandsByFields);
            function ($field, $operator, $operand, $allOperandsByField) use (&$root_or_mysql) {

                end($root_or_mysql);
                $last_key = key($root_or_mysql);

                if ($operator == '=') {
                    $root_or_mysql[ $last_key ][] = " $field = {$operand->getValue()} ";
                }
                elseif ($operator == '<') {
                    $root_or_mysql[ $last_key ][] = " $field < {$operand->getMaximum()} ";
                }
                elseif ($operator == '>') {
                    $root_or_mysql[ $last_key ][] = " $field > {$operand->getMinimum()} ";
                }

            },
            function () use (&$root_or_mysql) {
                end($root_or_mysql);
                $last_key = key($root_or_mysql);
                $root_or_mysql[ $last_key ] = implode(' AND ', $root_or_mysql[ $last_key ]);
            }
        );

        $converter->convert( $filter );

        $mysql_where = '('.implode(') OR (', $root_or_mysql).')';

        $this->assertEquals(
            "( field_1 = 2  AND  field_2 > 4 ) OR ( field_1 = 2  AND  field_2 < -4 )",
            $mysql_where
        );
    }

    /**
     */
    public function test_convert_with_sql_converter()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            ['or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_4', '!=', null],
        ]))
        // ->dump()
        ;

        $inline_sql = (new InlineSqlMinimalConverter())->convert( $filter );

        $this->assertEquals(
            "(field_1 = 2 AND field_2 > 4 AND field_3 IS NULL AND field_4 IS NOT NULL) OR (field_1 = 2 AND field_2 < -4 AND field_3 IS NULL AND field_4 IS NOT NULL)",
            $inline_sql
        );
    }

    /**
     */
    public function test_convert_with_elasticsearch_converter()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            [
                'or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ],
            ['field_3', '=', null],
            ['field_4', '!=', null],
        ]))
        // ->dump(true)
        ;

        $es_filter = (new ElasticSearchMinimalConverter())->convert( $filter );

        $this->assertEquals(
            [
                'bool' => [
                    'minimum_should_match' => 1,
                    'should' => [
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        "term" => [
                                            "field_1" => 2,
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_2" => [
                                                "gt" => 4,
                                            ],
                                        ],
                                    ],
                                    [
                                        "missing" => [
                                            "field" => 'field_3',
                                        ],
                                    ],
                                    [
                                        "exists" => [
                                            "field" => 'field_4',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    [
                                        "term" => [
                                            "field_1" => 2,
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_2" => [
                                                "lt" => -4,
                                            ],
                                        ],
                                    ],
                                    [
                                        "missing" => [
                                            "field" => 'field_3',
                                        ],
                                    ],
                                    [
                                        "exists" => [
                                            "field" => 'field_4',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $es_filter
        );
    }

    /**/
}
