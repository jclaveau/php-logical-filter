<?php
/**
 * ElasticSearchMinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Converter;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 * This class implements a converter for ElasticSearch.
 */
class ElasticSearchMinimalConverter extends MinimalConverter
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
            if ($operand->getValue() === null) {
                // https://www.elastic.co/guide/en/elasticsearch/guide/current/_dealing_with_null_values.html#_missing_query
                $new_rule = [
                    'missing' => [
                         'field' => $field,
                    ],
                ];
            }
            else {
                $new_rule = [
                    'term' => [
                        $field => $operand->getValue()
                    ]
                ];
            }
        }
        elseif ($operator == 'in') {
            $new_rule = [
                'terms' => [
                    $field => $operand->getPossibilities()
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
        elseif ($operator == '!=' && $operand->getValue() === null) {
            // https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
            $new_rule = [
                'exists' => [
                     'field' => $field,
                ],
            ];
        }
        elseif ($operator == 'regexp') {
            // https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
            $new_rule = [
                'regexp' => [
                     $field => [
                        'value' => $operand->getPattern()
                        // 'flags' => 'INTERSECTION|COMPLEMENT|EMPTY',
                        // 'max_determinized_states' => 2000
                    ],
                ],
            ];
        }
        else {
            throw new \InvalidArgumentException(
                "Unhandled operator '$operator' during ES query generation"
            );
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
