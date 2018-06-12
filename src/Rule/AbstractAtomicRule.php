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
     * @return AbstractAtomicRule $this
     */
    public final function renameField(array $renamings)
    {
        if (isset($renamings[$this->field]))
            $this->field = $renamings[$this->field];

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
