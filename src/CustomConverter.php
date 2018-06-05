<?php
/**
 * CustomConverter
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
class CustomConverter implements ConverterInterface
{
    /** @var protected array $callbacks */
    protected $callbacks = [];

    /**
     */
    public function __construct(callable $onOpenOr, callable $onAndPossibility, callable $onCloseOr)
    {
        $this->callbacks = [
            'onOpenOr'         => $onOpenOr,
            'onAndPossibility' => $onAndPossibility,
            'onCloseOr'        => $onCloseOr,
        ];
    }

    /**
     * Pseudo-event called while opening a case of the root Or of the
     * filter.
     */
    public function onOpenOr()
    {
        call_user_func( $this->callbacks['onOpenOr'] );
    }

    /**
     * Pseudo-event called while for each And operand of the root Or.
     * These operands must be only atomic Rules.
     */
    public function onAndPossibility($field, $operator, $operand, array $allOperandsByField)
    {
        call_user_func(
            $this->callbacks['onAndPossibility'],
            $field,
            $operator,
            $operand,
            $allOperandsByField
        );
    }

    /**
     * Pseudo-event called while closing a case of the root Or of the
     * filter.
     */
    public function onCloseOr()
    {
        call_user_func( $this->callbacks['onCloseOr'] );
    }

    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter )
    {
        $rootOr = $filter->simplify()->getRules();

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
