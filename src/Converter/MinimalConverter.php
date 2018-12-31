<?php
/**
 * MinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Converter;

use       JClaveau\LogicalFilter\Converter\ConverterInterface;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 * Basic tools to implements minimal converters: Converters that can
 * handle simplified filters with or/and/atomic structure.
 */
abstract class MinimalConverter implements ConverterInterface
{
    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter )
    {
        $rootOr = $filter->simplify(['force_logical_core' => true])->getRules();

        // TODO remove this once TrueRule implemented https://github.com/jclaveau/php-logical-filter/issues/59
        if (null === $rootOr) {
            return $this;
        }

        if ( ! $rootOr->hasSolution()) {
            return $this;
        }

        foreach ($rootOr->getOperands() as $andOperand) {
            $this->onOpenOr();
            $operandsByFields = $andOperand->groupOperandsByFieldAndOperator();

            foreach ($operandsByFields as $field => $operandsByOperator) {
                foreach ($operandsByOperator as $operator => $operandsOfOperator) {
                    if (1 != count($operandsOfOperator)) {
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
