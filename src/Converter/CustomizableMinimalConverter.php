<?php
/**
 * CustomizableMinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Converter;

/**
 * This class implements a converter using callbacks for every pseudo-event
 * related to the rules parsing.
 */
class CustomizableMinimalConverter extends MinimalConverter
{
    /** @var protected array $callbacks */
    protected $callbacks = [];

    /**
     */
    public function __construct(
        callable $onOpenOr,
        callable $onAndPossibility,
        callable $onCloseOr
    ) {
        $this->callbacks = get_defined_vars();
    }

    /**
     */
    public function onOpenOr()
    {
        call_user_func( $this->callbacks[ __FUNCTION__ ] );
    }

    /**
     */
    public function onCloseOr()
    {
        call_user_func( $this->callbacks[ __FUNCTION__ ] );
    }

    /**
     * Pseudo-event called while for each And operand of the root Or.
     * These operands must be only atomic Rules.
     */
    public function onAndPossibility($field, $operator, $operand, array $allOperandsByField)
    {
        call_user_func(
            $this->callbacks[ __FUNCTION__ ],
            $field,
            $operator,
            $operand,
            $allOperandsByField
        );
    }

    /**/
}
