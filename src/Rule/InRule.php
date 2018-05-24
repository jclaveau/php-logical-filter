<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * This class represents a rule that expect a value to belong to a list of others.
 */
class InRule extends OrRule
{
    /**
     * @param string $field         The field to apply the rule on.
     * @param array  $possibilities The values the field can belong to.
     */
    public function __construct( $field, array $possibilities )
    {
        $this->field = $field;
        $this->addPossibilities( $possibilities );
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
     * @return array
     *
     * @param  array possibilities
     *
     * @return InRule $this
     */
    public function addPossibilities(array $possibilities)
    {
        foreach ($possibilities as $possibility) {
            if ($possibility instanceof AbstractRule) {
                throw new \InvalidArgumentException(
                    "A possibility cannot be a rule: "
                    . var_export($possibility, true)
                );
            }

            $this->operands[] = new EqualRule($this->field, $possibility);
        }

        return $this;
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

    /**
     * Clones the rule and its operands.
     *
     * @return InRule A copy of the current instance with copied operands.
     */
    public function copy()
    {
        $possibilities = [];
        foreach ($this->operands as $operand) {
            $possibilities[] = $operand->getValue();
        }

        $copiedRule = new InRule( $this->field, $possibilities );

        return $copiedRule;
    }

    /**/
}
