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
    public function __construct( $field, array $possibilities )
    {
        $this->addOperand(new InRule($field, $possibilities));
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
    public function setPossibilities(array $possibilities)
    {
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
            if (!isset($options[ $default_option ]))
                $options[ $default_option ] = $default_value;
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
     */
    public function toString(array $options=[])
    {
        try {
            // if (!$this->changed)
                // return $this->cache;

            // $this->changed = false;

            $operator = self::operator;

            $stringified_possibilities = '[' . implode(', ', array_map(function($possibility) {
                return var_export($possibility, true);
            }, $this->getPossibilities()) ) .']';

            // return $this->cache = "['{$this->getField()}', '$operator', stringified_possibilities]";
            return "['{$this->getField()}', '$operator', $stringified_possibilities]";
        }
        catch (\LogicException $e) {
            return parent::toString();
        }
    }

    /**/
}
