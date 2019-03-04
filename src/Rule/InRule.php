<?php
/**
 * InRule
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

/**
 * This class represents a rule that expect a value to belong to a list of others.
 */
class InRule extends OrRule
{
    use Trait_RuleWithField;

    /** @var string operator */
    const operator = 'in';

    /** @var array $native_possibilities */
    protected $native_possibilities = [];

    /**
     * @param string $field         The field to apply the rule on.
     * @param mixed  $possibilities The values the field can belong to.
     */
    public function __construct($field, $possibilities, array $options=[])
    {
        if (! empty($options)) {
            $this->setOptions($options);
        }

        $this->field = $field;
        $this->addPossibilities( $possibilities );
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        return array_values($this->native_possibilities);
    }

    /**
     * @param  mixed possibilities
     *
     * @return InRule $this
     */
    public function addPossibilities($possibilities)
    {
        if (    is_object($possibilities)
            && $possibilities instanceof \IteratorAggregate
            && method_exists($possibilities, 'toArray')
        ) {
            $possibilities = $possibilities->toArray();
        }

        if ( ! is_array($possibilities)) {
            $possibilities = [$possibilities];
        }

        $possibilities = array_map([$this, 'checkOperandAndExtractValue'], $possibilities);

        // unique possibilities
        foreach ($possibilities as &$possibility) {
            if (is_scalar($possibility)) {
                $id = hash('crc32b', $possibility);
            }
            else {
                $id = hash('crc32b', serialize($possibility));
            }

            if ( ! isset($this->native_possibilities[ $id ])) {
                $this->native_possibilities[ $id ] = $possibility;
                $require_cache_flush               = true;
            }
        }

        if (isset($require_cache_flush)) {
            $this->flushCache();
        }

        return $this;
    }

    /**
     * @param  array possibilities
     *
     * @return InRule $this
     */
    public function addOperand( AbstractRule $operand )
    {
        $this->addPossibilities([$operand->getValue()]);

        return $this;
    }

    /**
     * @param  mixed possibilities
     *
     * @return InRule $this
     */
    public function setPossibilities($possibilities)
    {
        $this->native_possibilities = [];
        $this->addPossibilities($possibilities);
        $this->flushCache();

        return $this;
    }

    /**
     * @param  array possibilities
     *
     * @return InRule $this
     */
    public function setOperands(array $operands)
    {
        $this->addPossibilities($operands);

        return $this;
    }

    /**
     *
     */
    protected function checkOperandAndExtractValue($operand)
    {
        if ( ! $operand instanceof AbstractAtomicRule) {
            return $operand;
        }

        if ( ! ($operand instanceof EqualRule && $operand->getField() == $this->field) ) {
            throw new \InvalidArgumentException(
                "Trying to set an invalid operand of an InRule: "
                .var_export($operand, true)
            );
        }

        return $operand->getValue();
    }

    /**
     * @return InRule $this
     */
    public function getOperands()
    {
        if ( ! empty($this->cache['operands'])) {
            return $this->cache['operands'];
        }

        $operands = [];
        foreach ($this->native_possibilities as $value) {
            $operands[] = new EqualRule($this->field, $value);
        }

        return $this->cache['operands'] = $operands;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getPossibilities();
    }

    /**
     * @param array $options   + show_instance=false Display the operator of the rule or its instance id
     *
     * @return array
     */
    public function toArray(array $options=[])
    {
        $default_options = [
            'show_instance' => false,
        ];
        foreach ($default_options as $default_option => &$default_value) {
            if ( ! isset($options[ $default_option ])) {
                $options[ $default_option ] = $default_value;
            }
        }

        $class = get_class($this);

        if ( ! $options['show_instance'] && isset($this->cache['array'])) {
            return $this->cache['array'];
        }

        $array = [
            $this->getField(),
            $options['show_instance'] ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];

        if ( ! $options['show_instance']) {
            return $this->cache['array'] = $array;
        }
        else {
            return $array;
        }
    }

    /**
     */
    public function toString(array $options=[])
    {
        if (isset($this->cache['string'])) {
            return $this->cache['string'];
        }

        $operator = self::operator;

        $stringified_possibilities = '[' . implode(', ', array_map(function($possibility) {
            return var_export($possibility, true);
        }, $this->getPossibilities()) ) .']';

        return $this->cache['string'] = "['{$this->getField()}', '$operator', $stringified_possibilities]";
    }

    /**
     */
    public function isNormalizationAllowed(array $contextual_options)
    {
        if (null === ($threshold = $this->getOption('in.normalization_threshold', $contextual_options))) {
            return false;
        }

        return count($this->native_possibilities) <= $threshold;
    }

    /**
     * @return bool If the InRule can have a solution or not
     */
    public function hasSolution(array $contextual_options=[])
    {
        return ! empty($this->getPossibilities());
    }

    /**
     * There is no negations into an InRule
     */
    public function removeNegations(array $contextual_options)
    {
        return $this;
    }

    /**/
}
