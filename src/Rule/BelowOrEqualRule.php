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
        $maximum = $this->operands[0]->getMaximum();
        $value   = $this->operands[1]->getValue();

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
        $maximumField = $this->operands[0]->getField();
        $valueField   = $this->operands[1]->getField();

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
     */
    public function toArray($debug=false)
    {
        try {
            // TODO replace this rule by a simple OrRule?
            return [
                $this->getField(),
                $debug ? $this->getInstanceId() : self::operator,
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
