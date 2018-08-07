<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a >= x
 */
class AboveOrEqualRule extends OrRule
{
    /** @var string operator */
    const operator = '>=';

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can above to.
     */
    public function __construct( $field, $minimum )
    {
        $this->addOperand( new AboveRule($field, $minimum) );
        $this->addOperand( new EqualRule($field, $minimum) );
    }

    /**
     */
    public function getMinimum()
    {
        $minimum = $this->getOperandAt(0)->getMinimum();
        $value   = $this->getOperandAt(1)->getValue();

        if ($value != $minimum) {
            throw new \RuntimeException(
                "The value of the EqualRule (".var_export($value, true).") "
                ."and the minimum of the AboveRule (".var_export($minimum, true).") "
                ."of an ". __CLASS__ ." do not match anymore"
            );
        }

        return $minimum;
    }

    /**
     */
    public function getField()
    {
        $minimumField = $this->getOperandAt(0)->getField();
        $valueField   = $this->getOperandAt(1)->getField();

        if ($minimumField != $valueField) {
            // TODO if this case occures, the current object should be
            // replaced by a simple OrRule
            throw new \RuntimeException(
                "The field of the EqualRule (".var_export($valueField, true).") "
                ."and the field of the AboveRule (".var_export($minimumField, true).") "
                ."of an ". __CLASS__ ." do not match anymore"
            );
        }

        return $minimumField;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getMinimum();
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
