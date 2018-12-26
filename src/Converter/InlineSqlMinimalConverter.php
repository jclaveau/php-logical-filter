<?php
/**
 * InlineSqlMinimalConverter
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Converter;
use       JClaveau\LogicalFilter\LogicalFilter;
use       JClaveau\LogicalFilter\Rule\EqualRule;
use       JClaveau\LogicalFilter\Rule\NotEqualRule;
use       JClaveau\LogicalFilter\Rule\AboveRule;
use       JClaveau\LogicalFilter\Rule\BelowRule;
use       JClaveau\LogicalFilter\Rule\RegexpRule;
use       JClaveau\LogicalFilter\Rule\InRule;
use       JClaveau\LogicalFilter\Rule\NotInRule;
use       JClaveau\LogicalFilter\Rule\AboveOrEqualRule;
use       JClaveau\LogicalFilter\Rule\BelowOrEqualRule;

/**
 * This class implements a converter for MySQL.
 */
class InlineSqlMinimalConverter extends MinimalConverter
{
    /** @var array $output */
    protected $output = [];

    /** @var array $parameters */
    protected $parameters = [];

    /**
     * @return string parameter id
     */
    public function addParameter($value)
    {
        if (is_numeric($value))
            return $value;

        $uid = 'param_'.hash('crc32b', serialize($value));

        if (isset($this->parameters[$uid]))
            return ':'.$uid;

        $this->parameters[$uid] = $value;

        return ':'.$uid;
    }

    /**
     * @param LogicalFilter $filter
     */
    public function convert( LogicalFilter $filter )
    {
        $this->output = [];
        parent::convert($filter);
        return [
            'sql' => ! $this->output
                   ? '1' // True
                   : '('.implode(') OR (', $this->output).')',
            'parameters' => $this->parameters,
        ];
    }

    /**
     */
    public function onOpenOr()
    {
        $this->output[] = [];
    }

    /**
     */
    public function onCloseOr()
    {
        $last_key = $this->getLastOrOperandKey();
        $this->output[ $last_key ] = implode(' AND ', $this->output[ $last_key ]);
    }

    /**
     * Pseudo-event called while for each And operand of the root Or.
     * These operands must be only atomic Rules.
     */
    public function onAndPossibility($field, $operator, $rule, array $allOperandsByField)
    {
        if ($rule instanceof EqualRule) {
            $value = $rule->getValue();
        }
        elseif ($rule instanceof InRule) {
            $value = $rule->getPossibilities();
        }
        elseif ($rule instanceof NotInRule) {
            $operator = 'NOT IN';
            $value = $rule->getPossibilities();
        }
        elseif ($rule instanceof AboveRule) {
            $value = $rule->getLowerLimit();
        }
        elseif ($rule instanceof BelowRule) {
            $value = $rule->getUpperLimit();
        }
        elseif ($rule instanceof AboveOrEqualRule) {
            $value = $rule->getMinimum();
        }
        elseif ($rule instanceof BelowOrEqualRule) {
            $value = $rule->getMaximum();
        }
        elseif ($rule instanceof NotEqualRule) {
            $value = $rule->getValue();
        }
        elseif ($rule instanceof RegexpRule) {
            $value = RegexpRule::php2mariadbPCRE( $rule->getPattern() );
        }
        else {
            throw new \InvalidArgumentException(
                "Unhandled operator '$operator' during SQL query generation"
            );
        }

        if (gettype($value) == 'integer') {
        }
        elseif (gettype($value) == 'double') {
            // TODO disable locale to handle separators
        }
        elseif ($value instanceof \DateTime) {
            $value = "'" . $value->format('Y-m-d H:i:s') . "'";
        }
        elseif (gettype($value) == 'string') {
            $value = $this->addParameter($value);
        }
        elseif (gettype($value) == 'array') {
            $sql_part = [];
            foreach ($value as $possibility) {
                $sql_part[] = $this->addParameter($possibility);
            }
            $value = '(' . implode(', ', $sql_part) . ')';
        }
        elseif ($value === null) {
            $value = "NULL";
            if ($rule instanceof EqualRule) {
                $operator = 'IS';
            }
            elseif ($rule instanceof NotEqualRule) {
                $operator = 'IS NOT';
            }
            else {
                throw new \InvalidArgumentException(
                    "NULL is only handled for equality / difference"
                );
            }
        }
        else {
            throw new \InvalidArgumentException(
                "Unhandled type of value: ".gettype($value). ' | ' .var_export($value, true)
            );
        }

        $operator = strtoupper($operator);

        $new_rule = "$field $operator $value";

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
        $this->output[ $last_key ][] = $rule;
    }

    /**/
}
