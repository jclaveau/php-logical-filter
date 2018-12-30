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
    /** @var string $field The field to apply the rule on */
    protected $field;

    /**
     * @return string $field
     */
    final public function getField()
    {
        return $this->field;
    }

    /**
     * @return string $field
     */
    final public function setField( $field )
    {
        if ( ! is_scalar($field)) {
            throw new \InvalidArgumentEXception(
                "\$field property of a logical rule must be a scalar contrary to: "
                .var_export($field, true)
            );
        }

        if ($this->field != $field) {
            $this->field = $field;
            $this->flushCache();
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return AbstractAtomicRule $this
     */
    final public function renameField($renamings)
    {
        if (is_callable($renamings)) {
            $this->setField( call_user_func($renamings, $this->field) );
        }
        elseif (is_array($renamings)) {
            if (isset($renamings[$this->field])) {
                $this->setField( $renamings[$this->field] );
            }
        }
        else {
            throw new \InvalidArgumentException(
                "\$renamings MUST be a callable or an associative array "
                ."instead of: " . var_export($renamings, true)
            );
        }

        return $this;
    }

    /**/
}
