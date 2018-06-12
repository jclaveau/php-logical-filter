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

    /**
     * @return string $field
     */
    public final function getField()
    {
        return $this->field;
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
            $this->field = call_user_func($renamings, $this->field);
        }
        elseif (is_array($renamings)) {
            if (isset($renamings[$this->field]))
                $this->field = $renamings[$this->field];
        }
        else {
            throw new \InvalidArgumentException(
                "\$renamings MUST be a callable or an associative array "
                ."instead of: " . var_export($renamings, true)
            );
        }

        return $this;
    }

    /**
     * Atomic rules are always simplified
     *
     * @return bool
     */
    public function isSimplified()
    {
        return true;
    }

    /**/
}
