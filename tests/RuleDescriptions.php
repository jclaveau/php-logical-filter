<?php
namespace JClaveau\LogicalFilter\Tests;

/**
 * This file gathers sets of rules to use in datatproviders of test cases.
 * Listing rule cases here avoids forgetting some and ensure a better coverage.
 */
class RuleDescriptions extends \PHPUnit_Framework_TestCase
{
    public static function listStatementOperators()
    {
        return [
            '=',
            // '=null',
            '<',
            '>',
            '!=',
            // '!=null',
            'in',
            '!in',
            '>=',
            '<=',
            '><',
            '=><',
            '><=',
            '=><=',
            // 'regexp',
        ];
    }

    /**
     * Combines operators
     *
     * @return Array
     * (
     *     [0] => < vs =
     *     [1] => > vs =
     *     [2] => > vs <
     *     [3] => != vs =
     *     [4] => != vs <
     *     [5] => != vs >
     *     [6] => in vs =
     *     [7] => in vs <
     *     [8] => in vs >
     *     [9] => in vs !=
     *     [10] => !in vs =
     *     [11] => !in vs <
     *     [12] => !in vs >
     *     [13] => !in vs !=
     *     [14] => !in vs in
     *     [15] => >= vs =
     *     ...
     *     [60] => =><= vs !in
     *     [61] => =><= vs >=
     *     [62] => =><= vs <=
     *     [63] => =><= vs ><
     *     [64] => =><= vs =><
     *     [65] => =><= vs ><=
     * )
     *
     */
    public static function listStatementRuleCombinations()
    {
        $operators = $operators_2 = self::listStatementOperators();

        $out = [];

        $processed_operators = [];
        foreach ($operators as $i => $operator) {

            foreach ($processed_operators as $j => $processed_operator) {
                if ($operator == $processed_operator) {
                    continue;
                }

                $out[] = $operator.' vs '.$processed_operator;
            }

            $processed_operators[] = $operator;
        }

        return $out;
    }

    /**
     */
    public static function listValidMinimalistic()
    {
        return [
            ['field_1', '=', 2],
            ['field_1', '=', null],
            ['field_1', '<', 2],
            ['field_1', '>', 2],
            ['field_1', '!=', 'a'],
            ['field_1', '!=', null],
            ['field_1', 'in', ['a', 'b', 'c', null]],
            ['field_1', '!in', [2, 3]],
            ['field_1', '>=', 2],
            ['field_1', '<=', 2],
            ['field_1', '><', [2, 3]],
            ['field_1', '=><', [2, 3]],
            ['field_1', '><=', [2, 3]],
            ['field_1', '=><=', [2, 3]],
            ['field_1', 'regexp', '/^lalala*$/'],
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            ['not',
                ['field', '>', 3],
            ],
        ];
    }

    /**/
}
