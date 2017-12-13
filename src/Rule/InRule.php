<?php
namespace JClaveau\CustomFilter\Rule;

/**
 * This class represents a rule that expect a value to belong to a list of others.
 */
class InRule extends OrRule
{
    /**
     */
    public function __construct( array $possibilities )
    {
        foreach ($possibilities as $i => $possibility) {
            if ($possibility instanceof AbstractRule) {
                throw new \InvalidArgumentException(
                    '$possibilities must be an array of non rules'
                );
            }

            $possibilities[$i] = new EqualRule($possibility);
        }

        $this->operands = $possibilities;
    }

    /**
     * @return $this
     */
    public function combineWith( InRule $other_rule )
    {
        $this->operands = array_intersect(
            $this->operands,
            $other_rule->getOperands()
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        return $this->operands;
    }

    /**
     * If the array of possibilities is empty, this rule has no solution and the
     * filter neither so the filtering process can be stopped.
     *
     * @return bool
     */
    public function hasSolution()
    {
        return !empty( $this->operands );
    }

    /**/
}
