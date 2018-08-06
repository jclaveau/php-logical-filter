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
    /** @var string operator */
    const operator = 'in';

    /** @var integer simplification_threshold */
    const simplification_threshold = 20;

    /** @var string $field */
    protected $field;

    /** @var string $field */
    protected $simplification_allowed = true;

    /** @var array $native_possibilities */
    protected $native_possibilities = [];

    /** @var array $cache */
    protected $cache = [];

    /**
     * @param string $field         The field to apply the rule on.
     * @param mixed  $possibilities The values the field can belong to.
     */
    public function __construct( $field, $possibilities )
    {
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
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return AbstractAtomicRule $this
     */
    public final function renameFields($renamings)
    {
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

        // $unique_possibilities
        foreach ($possibilities as $possibility) {
            $possibility = $this->checkOperandAndExtractValue($possibility);

            $this->native_possibilities[ hash('crc32b', var_export($possibility, true)) ]
                = $possibility;
        }

        $this->cache['operands'] = [];

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

        return $this;
    }

    /**
     * @param  array possibilities
     *
     * @return InRule $this
     */
    public function setOperands(array $operands)
    {
        $possibilities = array_map([$this, 'checkOperandAndExtractValue'], $operands);
        $this->addPossibilities($possibilities);

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
        extract($options);

        $class = get_class($this);

        return [
            $this->getField(),
            $show_instance ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];
    }

    /**
     */
    public function toString(array $options=[])
    {
        // if (!$this->changed)
            // return $this->cache;

        // $this->changed = false;

        $operator = self::operator;

        $stringified_possibilities = '[' . implode(', ', array_map(function($possibility) {
            return var_export($possibility, true);
        }, $this->getPossibilities()) ) .']';

        return "['{$this->getField()}', '$operator', $stringified_possibilities]";
    }


    /**
     */
    public function isSimplificationAllowed()
    {
        return count($this->native_possibilities) < self::simplification_threshold;
    }

    /**/
}
