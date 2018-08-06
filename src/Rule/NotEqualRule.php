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

    /** @var array $cache */
    protected $cache = [
        'array'  => null,
        'string' => null,
    ];

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
        if (!$this->isSimplificationAllowed())
            return $this;

        $this->moveSimplificationStepForward( self::rootify_disjunctions );
        return $this;
    }

    /**
     */
    public function getField()
    {
        return $this->null_field !== null
            ? $this->null_field
            : $this->getOperandAt(0)->getField();
    }

    /**
     */
    public function getValue()
    {
        return $this->null_field !== null
            ? null
            : $this->getOperandAt(0)->getValue();
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
            if (!isset($options[ $default_option ]))
                $options[ $default_option ] = $default_value;
        }
        extract($options);

        return [
            $this->getField(),
            $show_instance ? $this->getInstanceId() : self::operator,
            $this->getValue(),
        ];
    }

    /**
     */
    public function toString(array $options=[])
    {
        if (!empty($this->cache['string']))
            return $this->cache['string'];

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
    public function hasSolution()
    {
        return true;
    }

    /**/
}
