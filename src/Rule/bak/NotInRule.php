<?php
namespace JClaveau\LogicalFilter;

/**
 * This class represents a rule that expect a value to belong to a list of others.
 */
class NotInRule extends Rule
{
    /**
     * This property should never be null.
     *
     * @var array $possibilities
     */
    protected $possibilities;

    /**
     */
    public function __construct( array $possibilities )
    {
        $this->possibilities = $possibilities;
    }

    /**
     * @return $this
     */
    public function combineWith( Rule $other_rule )
    {
        if ($other_rule instanceof NotInRule) {
            $this->possibilities = array_merge(
                $this->possibilities,
                $other_rule->getPossibilities()
            );
        } else {
            throw new \Exception(
                'Rule combination with '.get_class($other_rule).' not implemented'
            );
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        return $this->possibilities;
    }

    /**
     * NotIn rule will always have a solution.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}
