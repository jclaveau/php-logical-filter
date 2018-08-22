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
    use Trait_RuleWithOptions;

    /** @var string operator */
    const operator = 'in';

    /** @var string $field */
    protected $field;

    /** @var array $native_possibilities */
    protected $native_possibilities = [];

    /** @var array $cache */
    protected $cache = [
        'array'       => null,
        'string'      => null,
        'semantic_id' => null,
    ];

    /**
     * @param string $field         The field to apply the rule on.
     * @param mixed  $possibilities The values the field can belong to.
     */
    public function __construct( $field, $possibilities, array $options=[] )
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }

        $this->field = $field;
        $this->addPossibilities( $possibilities );
    }

    /**
     * @return string The field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return $this
     */
    public function setField($field)
    {
        if ($this->field != $field) {
            $this->field = $field;
            $this->flushCache();
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return AbstractAtomicRule $this
     */
    public final function renameFields($renamings)
    {
        $old_field = $this->field;

        if (is_callable($renamings)) {
            $this->field = call_user_func($renamings, $this->field);
        }
        elseif (is_array($renamings)) {
            if (isset($renamings[$this->field]))
                $this->field = $renamings[$this->field];
        }
        else {
            throw new \InvalidArgumentException(
                "\$renamings MUST be a callable or an associative array "
                ."instead of: " . var_export($renamings, true)
            );
        }

        if ($old_field != $this->field)
            $this->flushCache();

        return $this;
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        return array_values( $this->native_possibilities );
    }

    /**
     * @param  mixed possibilities
     *
     * @return InRule $this
     */
    public function addPossibilities($possibilities)
    {
        if (!is_array($possibilities))
            $possibilities = [$possibilities];

        $possibilities = array_map([$this, 'checkOperandAndExtractValue'], $possibilities);

        // unique possibilities
        foreach ($possibilities as &$possibility) {

            if (is_scalar($possibility))
                $id = hash('crc32b', $possibility);
            else
                $id = hash('crc32b', serialize($possibility));

            if (!isset($this->native_possibilities[ $id ])) {
                $this->native_possibilities[ $id ] = $possibility;
                $require_cache_flush = true;
            }
        }

        if (isset($require_cache_flush))
            $this->flushCache();

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
        if (! $operand instanceof AbstractAtomicRule)
            return $operand;

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
        if (!empty($this->cache['operands'])) {
            return $this->cache['operands'];
        }

        $operands = [];
        foreach ($this->native_possibilities as $value)
            $operands[] = new EqualRule($this->field, $value);

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
            if (!isset($options[ $default_option ]))
                $options[ $default_option ] = $default_value;
        }

        $class = get_class($this);

        if (!$options['show_instance'] && isset($this->cache['array']))
            return $this->cache['array'];

        $array = [
            $this->getField(),
            $options['show_instance'] ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];

        if (!$options['show_instance'])
            return $this->cache['array'] = $array;
        else
            return $array;
    }

    /**
     */
    public function toString(array $options=[])
    {
        if (isset($this->cache['string']))
            return $this->cache['string'];

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
        if (($threshold = $this->getOption('in.normalization_threshold', $contextual_options)) === null) {
            return false;
        }

        return count($this->native_possibilities) <= $threshold;
    }

    /**
     * @return bool If the InRule can have a solution or not
     */
    public function hasSolution()
    {
        return !empty($this->getPossibilities());
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
