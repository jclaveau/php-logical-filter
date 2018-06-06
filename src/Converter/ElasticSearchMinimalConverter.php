<?php
/**
 * ElasticSearchMinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Converter;
use       JClaveau\LogicalFilter\Converter\ConverterInterface;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 * This class implements a converter for ElasticSearch.
 */
class ElasticSearchMinimalConverter extends MinimalConverter implements ConverterInterface
{
    /** @var array $output */
    protected $output = [];

    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter )
    {
        $this->output = [
        ];

        parent::convert($filter);

        return [
            'bool' => [
                'minimum_should_match' => 1, // default
                'should' => $this->output,
            ]
        ];
    }

    /**
     */
    public function onOpenOr()
    {
        $this->output[] = [
            'bool' => [
                "must" => [

                ]
            ]
        ];
    }

    /**
     */
    public function onCloseOr()
    {
    }

    /**
     * Pseudo-event called while for each And operand of the root Or.
     * These operands must be only atomic Rules.
     */
    public function onAndPossibility($field, $operator, $operand, array $allOperandsByField)
    {
        if ($operator == '=') {
            $new_rule = [
                'term' => [
                    $field => $operand->getValue()
                ]
            ];
        }
        elseif ($operator == '<') {
            $new_rule = [
                'range' => [
                    $field => [
                        'lt' => $operand->getMaximum()
                    ],
                ]
            ];
        }
        elseif ($operator == '>') {
            $new_rule = [
                'range' => [
                    $field => [
                        'gt' => $operand->getMinimum()
                    ],
                ]
            ];
        }

        $this->appendToLastOrOperandKey($new_rule);
    }

    /**
     */
    protected function getLastOrOperandKey()
    {
        end($this->output);
        return key($this->output);
    }

    /**
     * @param string $rule
     */
    protected function appendToLastOrOperandKey($rule)
    {
        $last_key = $this->getLastOrOperandKey();
        $this->output[ $last_key ]['bool']['must'][] = $rule;
    }

    /**/
}
