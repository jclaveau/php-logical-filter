<?php
/**
 * PhpFilterer
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\LogicalFilter;
use       JClaveau\LogicalFilter\Rule\EqualRule;
use       JClaveau\LogicalFilter\Rule\BelowRule;
use       JClaveau\LogicalFilter\Rule\AboveRule;
use       JClaveau\LogicalFilter\Rule\NotEqualRule;

/**
 */
class PhpFilterer extends Filterer
{
    /**
     */
    public function validateRule ($field, $operator, $value, $row, $depth, $all_operands)
    {
        if ($operator === EqualRule::operator) {
            if ($value === null) {
                return !isset($row[$field]);
            }
            else {
                // TODO support strict comparisons
                return $row[$field] == $value;
            }
        }
        elseif ($operator === BelowRule::operator) {
            return $row[$field] < $value;
        }
        elseif ($operator === AboveRule::operator) {
            return $row[$field] > $value;
        }
        elseif ($operator === NotEqualRule::operator) {
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
        else {
            throw new \InvalidArgumentException(
                "Unhandled operator: " . $operator
            );
        }
    }

    /**/
}
