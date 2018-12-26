<?php
namespace JClaveau\LogicalFilter\Converter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class ConverterTest extends \AbstractTest
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
            ],
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
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_4', '!=', null],
                ['field_5', 'regexp', "/^(ab)+/i"],
                ['field_6', 'in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
                ['field_7', '!in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
                ['field_8', '=', new \DateTime('2018-11-12')],
                ['field_9', '>=', 4],
                ['field_10', '<=', -4],
            ]
        ))
        // ->dump()
        ;

        $inline_sql = (new InlineSqlMinimalConverter())->convert( $filter );

        $this->assertEquals(
            "(field_1 = 2 AND field_2 > 4 AND field_3 IS NULL AND field_4 IS NOT NULL AND field_5 REGEXP :param_b30f6679 AND field_6 IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_7 NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_8 = '2018-11-12 00:00:00' AND field_9 >= 4 AND field_10 <= -4) OR (field_1 = 2 AND field_2 < -4 AND field_3 IS NULL AND field_4 IS NOT NULL AND field_5 REGEXP :param_b30f6679 AND field_6 IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_7 NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_8 = '2018-11-12 00:00:00' AND field_9 >= 4 AND field_10 <= -4)",
            $inline_sql['sql']
        );

        $this->assertEquals(
            [
                'param_b30f6679' => '(?i)^(ab)+',
            ],
            $inline_sql['parameters']
        );
    }

    /**
     */
    public function test_convert_empty_filter_to_mysql()
    {
        $filter = new LogicalFilter();

        $inline_sql = (new InlineSqlMinimalConverter())->convert( $filter );

        $this->assertEquals(
            "1",
            $inline_sql['sql']
        );

        $this->assertEmpty(
            $inline_sql['parameters']
        );
    }

    /**
     */
    public function test_convert_with_elasticsearch_converter()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_2', '<', -4],
                ],
                ['field_3', '=', null],
                ['field_4', '!=', null],
                ['field_5', 'regexp', "/^(ab)+/i"],
                ['field_6', 'in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
                // ['field_7', '!in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
                ['field_9', '>=', 4],
                ['field_10', '<=', -4],
            ]
        ))
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
                                    [
                                        "regexp" => [
                                            "field_5" => [
                                                "value" => "/^(ab)+/i",
                                            ],
                                        ],
                                    ],
                                    [
                                        "terms" => [
                                            "field_6" => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_9" => [
                                                "gte" => 4,
                                            ],
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_10" => [
                                                "lte" => -4,
                                            ],
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
                                    [
                                        "regexp" => [
                                            "field_5" => [
                                                "value" => "/^(ab)+/i",
                                            ],
                                        ],
                                    ],
                                    [
                                        "terms" => [
                                            "field_6" => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_9" => [
                                                "gte" => 4,
                                            ],
                                        ],
                                    ],
                                    [
                                        "range" => [
                                            "field_10" => [
                                                "lte" => -4,
                                            ],
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

    /**
     */
    public function test_convert_empty_filter_to_elasticsearch()
    {
        $filter = new LogicalFilter();

        $es_filter = (new ElasticSearchMinimalConverter())->convert( $filter );

        $this->assertEquals(
            [
                'bool' => [
                    'minimum_should_match' => 1,
                    'should' => [],
                ],
            ],
            $es_filter
        );
    }

    /**/
}
