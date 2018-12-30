<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a >= x
 */
class AboveOrEqualRule extends OrRule
{
    use Trait_RuleWithField;

    /** @var string operator */
    const operator = '>=';

    /** @var mixed minimum */
    protected $minimum;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can below to.
     */
    public function __construct($field, $minimum, array $options=[])
    {
        if ( ! empty($options)) {
            $this->setOptions($options);
        }

        $this->field   = $field;
        $this->minimum = $minimum;
    }

    /**
     * @return mixed The minimum for the field of this rule
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * Defines the minimum of the current rule
     *
     * @param  mixed $minimum
     * @return BelowOrEqualRule $this
     */
    public function setMinimum($minimum)
    {
        if (null === $minimum) {
            throw new \InvalidArgumentException(
                "The minimum of a below or equal rule cannot be null"
            );
        }

        if ($this->minimum == $minimum) {
            return $this;
        }

        $this->minimum = $minimum;
        $this->flushCache();

        return $this;
    }

    /**
     * @return bool
     */
    public function isNormalizationAllowed(array $contextual_options=[])
    {
        return $this->getOption('above_or_equal.normalization', $contextual_options);
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
            new AboveRule($this->field, $this->minimum),
            new EqualRule($this->field, $this->minimum),
        ];

        return $this->cache['operands'] = $operands;
    }

    /**
     * Set the minimum and the field of the current instance by giving
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
            elseif ($operand instanceof AboveRule) {
                $aboveRuleField = $operand->getField();
                $aboveRuleValue = $operand->getLowerLimit();
            }
        }

        if (   2 != count($operands)
            || ! isset($equalRuleValue)
            || ! isset($aboveRuleValue)
            || $aboveRuleValue != $equalRuleValue
            || $aboveRuleField != $equalRuleField
        ) {
            throw new \InvalidArgumentException(
                "Operands must be an array of two rules like (field > minimum || field = minimum) instead of:\n"
                .var_export($operands, true)
            );
        }

        $this->setMinimum($aboveRuleValue);
        $this->setField($aboveRuleField);

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getMinimum();
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
