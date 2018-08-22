<?php
/**
 * Trait_RuleWithField
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

trait Trait_RuleWithField
{
    /** @var string $field */
    protected $field;

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
            // $this->flushCache();
        }

        return $this;
    }

    /**/
}
