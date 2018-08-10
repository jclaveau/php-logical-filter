<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a >= x
 */
class AboveOrEqualRule extends OrRule
{
    /** @var string operator */
    const operator = '>=';

    protected $lower_limit;
    protected $field;

    /**
     * @param string $field       The field to apply the rule on.
     * @param array  $lower_limit The value the field can above to.
     */
    public function __construct( $field, $lower_limit )
    {
        $this->field = $field;
        $this->lower_limit = $lower_limit;
    }

    /**
     * @deprecated getLowerLimit
     */
    public function getMinimum()
    {
        return $this->getLowerLimit();
    }

    /**
     * @deprecated getLowerLimit
     */
    public function getLowerLimit()
    {
        return $this->lower_limit;
    }

    /**
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getLowerLimit();
    }

    /**
     * @return array
     */
    public function getOperands()
    {
        return [
            new AboveRule($this->field, $this->lower_limit),
            new EqualRule($this->field, $this->lower_limit),
        ];
    }

    /**
     * @param  array possibilities
     *
     * @return InRule $this
     */
    public function setOperands(array $operands)
    {
        $equal_value = null;
        $above_value = null;

        foreach ($operands as $operand) {
            $operand->getValues();
            if ($operand instanceof EqualRule && $this->field == $operand->getField()) {
                $equal_value = $operand->getValue();
            }
            elseif ($operand instanceof AboveRule && $this->field == $operand->getField()) {
                $above_value = $operand->getMinimum();
            }
            else {
                throw new \LogicException(
                    "Setting invalid operand for $this: "
                    .var_export($operand, true)
                );
            }
        }

        if (!isset($equal_value) || !isset($above_value)) {
            throw new \LogicException(
                "Trying to set null values for $this: "
                .var_export([$equal_value, $above_value], true)
            );
        }

        if ($equal_value != $above_value) {
            throw new \LogicException(
                "Trying to set different values for $this: "
                .var_export([$equal_value, $above_value], true)
            );
        }

        return $this;
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
        try {
            // if (!$this->changed)
                // return $this->cache;

            // $this->changed = false;

            $operator = self::operator;

            // return $this->cache = "['{$this->getField()}', '$operator', stringified_possibilities]";
            return "['{$this->getField()}', '$operator', " . var_export($this->getValues(), true). "]";
        }
        catch (\LogicException $e) {
            return parent::toString();
        }
    }

    /**/
}
