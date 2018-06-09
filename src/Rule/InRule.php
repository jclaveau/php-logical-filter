<?php
/**
 * InRule
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

/**
 * This class represents a rule that expect a value to belong to a list of others.
 */
class InRule extends OrRule
{
    /** @var string operator */
    const operator = 'in';

    /** @var string $field */
    protected $field;

    /**
     * @param string $field         The field to apply the rule on.
     * @param mixed  $possibilities The values the field can belong to.
     */
    public function __construct( $field, $possibilities )
    {
        $this->field = $field;
        $this->addPossibilities( $possibilities );
    }

    /**
     * @return string The field
     */
    public function getField()
    {
        $fields = [$this->field];
        foreach ($this->operands as $operand)
            $fields[] = $operand->getField();

        $fields = array_unique($fields);
        if (count($fields) > 1) {
            throw new \RuntimeException(
                "Unable to retrieve the field of an " . __CLASS__ . " as "
                ."it contains now operands related to multiple fields"
            );
        }

        return reset($fields);
    }

    /**
     * @return array
     */
    public function getPossibilities()
    {
        $possibilities = [];
        foreach ($this->operands as $operand)
            $possibilities[] = $operand->getValue();

        return $possibilities;
    }

    /**
     * @param  mixed possibilities
     *
     * @return InRule $this
     */
    public function addPossibilities($possibilities)
    {
        if (!is_array($possibilities))
            $possibilities = [$possibilities];

        foreach ($possibilities as $possibility) {
            if ($possibility instanceof AbstractRule) {
                throw new \InvalidArgumentException(
                    "A possibility cannot be a rule: "
                    . var_export($possibility, true)
                );
            }

            $this->operands[] = new EqualRule($this->getField(), $possibility);
        }

        return $this;
    }

    /**
     * @param bool $debug=false
     */
    public function toArray($debug=false)
    {
        $description = [
            $this->getField(),
            $debug ? $this->getInstanceId() : self::operator,
            $this->getPossibilities()
        ];

        return $description;
    }

    /**/
}
