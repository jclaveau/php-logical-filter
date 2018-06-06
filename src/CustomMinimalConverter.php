<?php
/**
 * CustomMinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 * @version 0.1.0 (29/05/2018)
 */
namespace JClaveau\LogicalFilter;
use       JClaveau\LogicalFilter\Converter\ConverterInterface;

/**
 * This class implements a converter using callbacks for every pseudo-event
 * related to the rules parsing.
 */
class CustomMinimalConverter implements ConverterInterface
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
            $this->callbacks[__FUNCTION__ ],
            $field,
            $operator,
            $operand,
            $allOperandsByField
        );
    }

    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter )
    {
        $rootOr = $filter->simplify(['force_logical_core' => true])->getRules();

        if (!$rootOr->hasSolution())
            return $this;

        foreach ($rootOr->getOperands() as $andOperand) {

            $this->onOpenOr();
            $operandsByFields = $andOperand->groupOperandsByFieldAndOperator();

            foreach ($operandsByFields as $field => $operandsByOperator) {
                foreach ($operandsByOperator as $operator => $operandsOfOperator) {
                    if (count($operandsOfOperator) != 1) {
                        throw new \RuntimeException(
                             "Once a logical filter is simplified, there MUST be "
                            ."no more than one operand by operator instead of for '$field' / '$operator': "
                            .var_export($operandsOfOperator, true)
                        );
                    }

                    $operandsByFields[ $field ][ $operator ] = $operandsOfOperator[0];
                }
            }

            foreach ($operandsByFields as $field => $operandsByOperator) {
                foreach ($operandsByOperator as $operator => $operand) {
                    $this->onAndPossibility(
                        $field,
                        $operator,
                        $operand,
                        $operandsByFields
                    );
                }
            }

            $this->onCloseOr();
        }
    }

    /**/
}
