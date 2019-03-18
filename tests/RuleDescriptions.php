<?php
namespace JClaveau\LogicalFilter\Tests;

/**
 * This file gathers sets of rules to use in datatproviders of test cases.
 * Listing rule cases here avoids forgetting some and ensure a better coverage.
 */
class RuleDescriptions extends \PHPUnit_Framework_TestCase
{
    public static function listValidMinimalistic()
    {
        return [
            ['field_1', '=', 2],
            ['field_1', '=', null],
            ['field_1', '<', 2],
            ['field_1', '>', 2],
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
            ['field_1', 'in', ['a', 'b', 'c', null]],
            ['field_1', '>=', 2],
            ['field_1', '<=', 2],
            ['field_1', '!=', 'a'],
            ['field_1', '!=', null],
            ['field_1', '!in', [2, 3]],
            ['field_1', '><', [2, 3]],
            ['field_1', '=><', [2, 3]],
            ['field_1', '><=', [2, 3]],
            ['field_1', '=><=', [2, 3]],
            ['field_1', 'regexp', '/^lalala*/'],
        ];
    }


    /**/
}
