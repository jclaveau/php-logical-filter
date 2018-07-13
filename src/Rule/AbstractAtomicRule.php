<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Atomic rules are those who cannot be simplified (so already are):
 * + null
 * + not null
 * + equal
 * + above
 * + below
 * Atomic rules are related to a field.
 */
abstract class AbstractAtomicRule extends AbstractRule
{
    /** @var string $field The field to apply the rule on */
    protected $field;

    /** @var bool $changed */
    protected $changed = true;

    /**
     * @return string $field
     */
    public final function getField()
    {
        return $this->field;
    }

    /**
     * @return string $field
     */
    public final function setField( $field )
    {
        if (!is_scalar($field)) {
            throw new \InvalidArgumentEXception(
                "\$field property of a logical rule must be a scalar contrary to: "
                .var_export($field, true)
            );
        }

        if ($this->field != $field) {
            $this->field   = $field;
            $this->changed = true;
        }

        return $this;
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return AbstractAtomicRule $this
     */
    public final function renameField($renamings)
    {
        if (is_callable($renamings)) {
            $this->setField( call_user_func($renamings, $this->field) );
        }
        elseif (is_array($renamings)) {
            if (isset($renamings[$this->field]))
                $this->setField( $renamings[$this->field] );
        }
        else {
            throw new \InvalidArgumentException(
                "\$renamings MUST be a callable or an associative array "
                ."instead of: " . var_export($renamings, true)
            );
        }

        return $this;
    }

    protected $cache;

    /**
     */
    public function toArray($debug=false)
    {
        if (!$this->changed)
            return $this->cache;

        $this->changed = false;

        $class = get_class($this);

        return $this->cache = [
            $this->getField(),
            $debug ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];
    }

    /**/
}
