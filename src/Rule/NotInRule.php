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
        if (!isset($this->operands[0])) {
            // TODO a NotRule with no operand should be simplified as
            //      a TrueRule
            throw new \LogicException(
                "Trying to get the field of a negation missing its operand"
            );
        }

        return $this->operands[0]->getField();
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        if ($this->operands[0] instanceof EqualRule) {
            // In can be simplified in =
            return [$this->operands[0]->getValue()];
        }

        return $this->operands[0]->getPossibilities();
    }

    /**
     * @return $this
     */
    public function setPossibilities(array $possibilities)
    {
        if ($this->operands[0] instanceof EqualRule) {
            // TODO this case should occure anympore while a NotInRule
            // may not have the same class once simplified
            $possibilities[] = $this->operands[0]->getValue();

            $this->operands[0] = new InRule(
                $this->operands[0]->getField(),
                array_unique($possibilities)
            );
        }
        elseif ($this->operands[0] instanceof InRule) {
            return $this->operands[0]->setPossibilities($possibilities);
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
    public function toArray($debug=false)
    {
        try {
            return [
                $this->getField(),
                $debug ? $this->getInstanceId() : self::operator,
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
