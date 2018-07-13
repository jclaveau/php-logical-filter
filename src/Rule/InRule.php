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

    /** @var integer simplification_threshold */
    const simplification_threshold = 20;

    /** @var string $field */
    protected $field;

    /** @var string $field */
    protected $simplification_allowed = true;

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
                ."it contains now operands related to multiple fields: "
                .implode(', ', $fields)
            );
        }

        return reset($fields);
    }

    /**
     * @param  array|callable Associative array of renamings or callable
     *                        that would rename the fields.
     *
     * @return AbstractAtomicRule $this
     */
    public final function renameFields($renamings)
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

        parent::renameFields($renamings);

        return $this;
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
            if ($possibility instanceof EqualRule && $possibility->getField() == $this->getField()) {
                $possibility = $possibility->getValue();
            }
            elseif ($possibility instanceof AbstractRule) {
                throw new \InvalidArgumentException(
                    "A possibility cannot be a rule: "
                    . var_export($possibility, true)
                );
            }

            $this->operands[] = new EqualRule($this->getField(), $possibility);
        }

        $this->simplification_allowed = count($this->operands) < self::simplification_threshold;

        return $this;
    }

    /**
     * @param  mixed possibilities
     *
     * @return InRule $this
     */
    public function setPossibilities($possibilities)
    {
        $this->operands = [];
        $this->addPossibilities($possibilities);

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getPossibilities();
    }

    /**
     */
    public function toArray($debug=false)
    {
        $class = get_class($this);

        return [
            $this->getField(),
            $debug ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];
    }

    /**
     */
    public function isSimplificationAllowed()
    {
        return $this->simplification_allowed;
    }

    /**/
}
