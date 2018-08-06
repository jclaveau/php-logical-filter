<?php
/**
 * BetweenRule
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

class BetweenRule extends AndRule
{
    /** @var string operator */
    const operator = '><';

    /**
     */
    public function __construct( $field, array $limits )
    {
        $this->addOperand( new AboveRule($field, $limits[0]) );
        $this->addOperand( new BelowRule($field, $limits[1]) );
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->getOperandAt(0)->getMinimum();
    }

    /**
     * @return mixed
     */
    public function getMaximum()
    {
        return $this->getOperandAt(1)->getMaximum();
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            $this->getMinimum(),
            $this->getMaximum(),
        ];
    }

    /**
     */
    public function getField()
    {
        $field1 = $this->getOperandAt(0)->getField();

        if (!$this->getOperandAt(1))
            return $field1;

        $field2 = $this->getOperandAt(1)->getField();

        if ($field1 != $field2) {
            // TODO if this case occures, the current object should be
            // replaced by a simple OrRule
            throw new \RuntimeException(
                "The field of the AboveRule (".var_export($field1, true).") "
                ."and the field of the BelowRule (".var_export($field2, true).") "
                ."of an ". __CLASS__ ." do not match anymore"
            );
        }

        return $field1;
    }

    /**
     * Checks if the range is valid.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return $this->getMaximum() > $this->getMinimum();
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

        // try {
            return [
                $this->getField(),
                $show_instance ? $this->getInstanceId() : $class::operator,
                $this->getValues(),
            ];
        // }
        // catch (\RuntimeException $e) {
            // return parent::toArray();
        // }
    }

    /**
     */
    public function toString(array $options=[])
    {
        try {
            // if (!$this->changed)
                // return $this->cache;

            // $this->changed = false;
            $class = get_called_class();

            $operator = $class::operator;

            $stringified_limits = '[' . implode(', ', array_map(function($limit) {
                return var_export($limit, true);
            }, $this->getValues()) ) .']';

            // return $this->cache = "['{$this->getField()}', '$operator', stringified_possibilities]";
            return "['{$this->getField()}', '$operator', $stringified_limits]";
        }
        catch (\LogicException $e) {
            return parent::toString();
        }
    }

    /**/
}
