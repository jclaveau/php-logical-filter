<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a ! in x
 */
class NotInRule extends NotRule
{
    /** @var string operator */
    const operator = '!in';

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, $possibilities )
    {
        $this->addOperand(new InRule($field, $possibilities));
    }

    /**
     */
    public function isNormalizationAllowed(array $contextual_options=[])
    {
        $operand = $this->getOperandAt(0);
        if ( ! $operand->getPossibilities()) {
            return false;
        }

        return $operand->isNormalizationAllowed($contextual_options);
    }

    /**
     */
    public function getField()
    {
        if (!$this->getOperandAt(0)) {
            // TODO a NotRule with no operand should be simplified as
            //      a TrueRule
            throw new \LogicException(
                "Trying to get the field of a negation missing its operand"
            );
        }

        return $this->getOperandAt(0)->getField();
    }

    /**
     */
    public function setField($field)
    {
        if (!$this->getOperandAt(0)) {
            // TODO a NotRule with no operand should be simplified as
            //      a TrueRule
            throw new \LogicException(
                "Trying to get the field of a negation missing its operand"
            );
        }

        return $this->getOperandAt(0)->getField($field);
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        if ($this->getOperandAt(0) instanceof EqualRule) {
            // In can be simplified in =
            return [$this->getOperandAt(0)->getValue()];
        }

        return $this->getOperandAt(0)->getPossibilities();
    }

    /**
     * @return $this
     */
    public function setPossibilities($possibilities)
    {
        if (    is_object($possibilities)
            && $possibilities instanceof \IteratorAggregate
            && method_exists($possibilities, 'toArray')
        ) {
            $possibilities = $possibilities->toArray();
        }

        if ($this->getOperandAt(0) instanceof EqualRule) {
            // TODO this case should occure anymore while a NotInRule
            // may not have the same class once simplified
            $possibilities[] = $this->getOperandAt(0)->getValue();

            $operands = [
                new InRule(
                    $this->getOperandAt(0)->getField(),
                    array_unique($possibilities)
                )
            ];

            $this->setOperands($operands);
        }
        elseif ($this->getOperandAt(0) instanceof InRule) {
            return $this->getOperandAt(0)->setPossibilities($possibilities);
        }
    }

    /**
     */
    public function getValues()
    {
        return $this->getPossibilities();
    }

    /**
     */
    public function hasSolution(array $contextual_options=[])
    {
        return true;
    }

    /**
     * @param array $options   + show_instance=false Display the operator of the rule or its instance id
     *
     * @return array
     */
    public function toArray(array $options=[])
    {
        $default_options = [
            'show_instance' => false,
        ];
        foreach ($default_options as $default_option => &$default_value) {
            if (!isset($options[ $default_option ])) {
                $options[ $default_option ] = $default_value;
            }
        }

        try {
            return [
                $this->getField(),
                $options['show_instance'] ? $this->getInstanceId() : self::operator,
                $this->getPossibilities(),
            ];
        }
        catch (\LogicException $e) {
            return parent::toArray();
        }
    }

    /**
     * @todo cache support
     */
    public function toString(array $options=[])
    {
        try {
            $operator = self::operator;

            $stringified_possibilities = '[' . implode(', ', array_map(function($possibility) {
                return var_export($possibility, true);
            }, $this->getPossibilities()) ) .']';

            return "['{$this->getField()}', '$operator', $stringified_possibilities]";
        }
        catch (\LogicException $e) {
            return parent::toString();
        }
    }

    /**/
}
