<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a != x
 */
class NotEqualRule extends NotRule
{
    /** @var string operator */
    const operator = '!=';

    /** @var string null_field */
    protected $null_field = null;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, $value )
    {
        if ($value === null) {
            $this->null_field = $field;
        }
        else {
            $this->addOperand(new EqualRule($field, $value));
        }
    }

    /**
     * Replace all the OrRules of the RuleTree by one OrRule at its root.
     *
     * @todo rename as RootifyDisjunjctions?
     * @todo return $this (implements a Rule monad?)
     *
     * @return OrRule copied operands with one OR at its root
     */
    public function rootifyDisjunctions()
    {
        $this->moveSimplificationStepForward( self::rootify_disjunctions );
        return $this;
    }

    /**
     */
    public function getField()
    {
        return $this->null_field !== null
            ? $this->null_field
            : $this->operands[0]->getField();
    }

    /**
     */
    public function getValue()
    {
        return $this->null_field !== null
            ? null
            : $this->operands[0]->getValue();
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $this->getField(),
            $debug ? $this->getInstanceId() : self::operator,
            $this->getValue(),
        ];
    }

    /**
     * By default, every atomic rule can have a solution by itself
     *
     * @return bool
     */
    public function hasSolution()
    {
        return true;
    }

    /**/
}