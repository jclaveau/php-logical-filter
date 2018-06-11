<?php
/**
 * PhpFilterer
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\Filterer\FiltererInterface;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 */
class PhpFilterer extends Filterer implements FiltererInterface
{
    /**
     */
    public function validateRule ($field, $operator, $value, $row, $all_operands)
    {
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
        else {
            throw new \InvalidArgumentException(
                "Unhandled operator"
            );
        }
    }

    /**/
}
