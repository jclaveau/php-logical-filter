<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a != x
 */
class NotEqualRule extends NotRule
{
    use Trait_RuleWithField;

    /** @var string operator */
    const operator = '!=';

    /** @var mixed value */
    protected $value;

    /**
     * @param string $field The field to apply the rule on.
     * @param array  $value The value the field can equal to.
     */
    public function __construct( $field, $value, array $options=[] )
    {
        $this->field = $field;
        $this->value = $value;

        // TODO use the operand instead of properties or don't create EqualRule
        parent::__construct(new EqualRule($field, $value), $options);
    }

    /**
     */
    public function isNormalizationAllowed(array $contextual_options=[])
    {
        $operand = $this->getOperandAt(0);

        $out = parent::isNormalizationAllowed()
            && $this->getOption('not_equal.normalization', $contextual_options)
            ;
        return $out;
    }

    /**
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     */
    public function getValues()
    {
        return $this->getValue();
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

        if (!$options['show_instance'] && isset($this->cache['array'])) {
            return $this->cache['array'];
        }

        $array = [
            $this->getField(),
            $options['show_instance'] ? $this->getInstanceId() : self::operator,
            $this->getValue(),
        ];

        if (!$options['show_instance']) {
            return $this->cache['array'] = $array;
        }
        else {
            return $array;
        }
    }

    /**
     */
    public function toString(array $options=[])
    {
        if (!empty($this->cache['string'])) {
            return $this->cache['string'];
        }

        $class = get_class($this);
        $operator = $class::operator;

        $stringified_value = var_export($this->getValues(), true);

        return $this->cache['string'] = "['{$this->getField()}', '$operator', $stringified_value]";
    }

    /**
     * By default, every atomic rule can have a solution by itself
     *
     * @return bool
     */
    public function hasSolution(array $simplification_options=[])
    {
        return true;
    }

    /**/
}
