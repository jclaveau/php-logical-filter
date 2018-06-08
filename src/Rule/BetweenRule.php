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
        return $this->operands[0]->getMinimum();
    }

    /**
     * @return mixed
     */
    public function getMaximum()
    {
        return $this->operands[1]->getMaximum();
    }

    /**
     */
    public function getField()
    {
        $field1 = $this->operands[0]->getField();
        $field2 = $this->operands[1]->getField();

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
        return is_null($this->getMaximum())
            || is_null($this->getMinimum())
            || $this->getMaximum() > $this->getMinimum();
    }

    /**
     * @param bool $debug=false
     */
    public function toArray($debug=false)
    {
        $description = [
            $this->getField(),
            $debug ? $this->getInstanceId() : self::operator,
            [
                $this->getMinimum(),
                $this->getMaximum(),
            ]
        ];

        return $description;
    }

    /**/
}
