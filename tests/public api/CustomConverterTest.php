<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class CustomConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     */
    public function test_convert_to_mysql_simple()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 2],
            [
                'or',
                ['field_2', '>', 4],
                ['field_2', '<', -4],
            ]
        ]);

        $mysql_where_part = "WHERE 1 ";

        $root_or_mysql = [];

        $converter = new CustomConverter(
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

    /**/
}
