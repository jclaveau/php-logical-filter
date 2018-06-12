<?php
/**
 * CustomizableFilterer
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Filterer;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 */
class CustomizableFilterer extends Filterer
{
    protected $rule_validator;

    /**
     */
    public function __construct( callable $rule_validator )
    {
        $this->rule_validator = $rule_validator;
    }

    /**
     */
    public function validateRule ($field, $operator, $value, $row, $all_operands)
    {
        return call_user_func_array( $this->rule_validator, get_defined_vars() );
    }

    /**/
}
