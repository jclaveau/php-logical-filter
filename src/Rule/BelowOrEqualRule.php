<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a <= x
 */
class BelowOrEqualRule extends OrRule
{
    use Trait_RuleWithField;

    /** @var string operator */
    const operator = '<=';

    /** @var mixed maximum */
    protected $maximum;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can below to.
     */
    public function __construct($field, $maximum, array $options=[])
    {
        if ( ! empty($options)) {
            $this->setOptions($options);
        }

        $this->field   = $field;
        $this->maximum = $maximum;
    }

    /**
     * @return mixed The maximum for the field of this rule
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * Defines the maximum of the current rule
     *
     * @param  mixed $maximum
     * @return BelowOrEqualRule $this
     */
    public function setMaximum($maximum)
    {
        if ($maximum === null) {
            throw new \InvalidArgumentException(
                "The maximum of a below or equal rule cannot be null"
            );
        }

        if ($this->maximum == $maximum) {
            return $this;
        }

        $this->maximum = $maximum;
        $this->flushCache();

        return $this;
    }

    /**
     * @return bool
     */
    public function isNormalizationAllowed(array $contextual_options=[])
    {
        return $this->getOption('below_or_equal.normalization', $contextual_options);
    }

    /**
     * @return array $operands
     */
    public function getOperands()
    {
        if ( ! empty($this->cache['operands'])) {
            return $this->cache['operands'];
        }

        $operands = [
            new BelowRule($this->field, $this->maximum),
            new EqualRule($this->field, $this->maximum),
        ];

        return $this->cache['operands'] = $operands;
    }

    /**
     * Set the maximum and the field of the current instance by giving
     * an array of opereands as parameter.
     *
     * @param  array            $operands
     * @return BelowOrEqualRule $this
     */
    public function setOperands(array $operands)
    {
        foreach ($operands as $operand) {
            if ($operand instanceof EqualRule) {
                $equalRuleField = $operand->getField();
                $equalRuleValue = $operand->getValue();
            }
            elseif ($operand instanceof BelowRule) {
                $belowRuleField = $operand->getField();
                $belowRuleValue = $operand->getUpperLimit();
            }
        }

        if (    count($operands) != 2
            || ! isset($equalRuleValue)
            || ! isset($belowRuleValue)
            || $belowRuleValue != $equalRuleValue
            || $belowRuleField != $equalRuleField
        ) {
            throw new \InvalidArgumentException(
                "Operands must be an array of two rules like (field < maximum || field = maximum) instead of:\n"
                .var_export($operands, true)
            );
        }

        $this->setMaximum($belowRuleValue);
        $this->setField($belowRuleField);

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getMaximum();
    }

    /**
     * @param array $options + show_instance=false Display the operator of the rule or its instance id
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

        try {
            // TODO replace this rule by a simple OrRule?
            return [
                $this->getField(),
                $options['show_instance'] ? $this->getInstanceId() : self::operator,
                $this->getValues(),
            ];
        }
        catch (\RuntimeException $e) {
            return parent::toArray();
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

        return $this->cache['string'] = "['{$this->getField()}', '$operator', " . var_export($this->getValues(), true). "]";
    }

    /**/
}
