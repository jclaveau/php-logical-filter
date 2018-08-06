<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a <= x
 */
class BelowOrEqualRule extends OrRule
{
    /** @var string operator */
    const operator = '<=';

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can below to.
     */
    public function __construct( $field, $maximum )
    {
        $this->operands[] = new BelowRule($field, $maximum);
        $this->operands[] = new EqualRule($field, $maximum);
    }

    /**
     */
    public function getMaximum()
    {
        $maximum = $this->getOperandAt(0)->getMaximum();
        $value   = $this->getOperandAt(1)->getValue();

        if ($value != $maximum) {
            throw new \RuntimeException(
                "The value of the EqualRule (".var_export($value, true).") "
                ."and the minimum of the AboveRule (".var_export($maximum, true).") "
                ."of an ". __CLASS__ ." do not match anymore"
            );
        }

        return $maximum;
    }

    /**
     */
    public function getField()
    {
        $maximumField = $this->getOperandAt(0)->getField();
        $valueField   = $this->getOperandAt(1)->getField();

        if ($maximumField != $valueField) {
            // TODO if this case occures, the current object should be
            // replaced by a simple OrRule
            throw new \RuntimeException(
                "The field of the EqualRule (".var_export($valueField, true).") "
                ."and the field of the AboveRule (".var_export($maximumField, true).") "
                ."of an ". __CLASS__ ." do not match anymore"
            );
        }

        return $maximumField;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getMaximum();
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

        try {
            // TODO replace this rule by a simple OrRule?
            return [
                $this->getField(),
                $show_instance ? $this->getInstanceId() : self::operator,
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
