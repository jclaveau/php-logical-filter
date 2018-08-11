<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * Logical negation:
 * @see https://en.wikipedia.org/wiki/Negation
 */
class NotRule extends AbstractOperationRule
{
    /** @var string operator */
    const operator = 'not';

    /**
     */
    public function __construct( AbstractRule $operand=null )
    {
        if (!$operand)
            return;

        // Negation has only one operand. If more is required, group them
        // into an AndRule
        $this->addOperand($operand);
    }

    /**
     * Transforms all composite rules in the tree of operands into
     * atomic rules.
     *
     * @todo use get_class instead of instanceof to avoid order issue
     *       in the conditions.
     *
     * @return array
     */
    public function negateOperand($remove_generated_negations=false)
    {
        if (!$this->operands)
            return $this;

        $operand = $this->getOperandAt(0);

        if (method_exists($operand, 'getField'))
            $field = $operand->getField();

        if ($operand instanceof AboveRule) {
            $new_rule = new OrRule([
                new BelowRule($field, $operand->getMinimum()),
                new EqualRule($field, $operand->getMinimum()),
            ]);
        }
        elseif ($operand instanceof BelowRule) {
            // ! (v >  a) : v <= a : (v < a || a = v)
            $new_rule = new OrRule([
                new AboveRule($field, $operand->getMaximum()),
                new EqualRule($field, $operand->getMaximum()),
            ]);
        }
        elseif ($operand instanceof NotRule) {
            // ! (  !  a) : a
            $new_rule = $operand->getOperandAt(0);
        }
        elseif ($operand instanceof EqualRule && $operand->getValue() === null) {
            $new_rule = new NotEqualRule($field, null);
        }
        elseif ($operand instanceof EqualRule) {
            // ! (v =  a) : (v < a) || (v > a)
            $new_rule = new OrRule([
                new AboveRule($field, $operand->getValue()),
                new BelowRule($field, $operand->getValue()),
            ]);
        }
        elseif ($operand instanceof AndRule) {
            // @see https://github.com/jclaveau/php-logical-filter/issues/40
            // ! (B && A) : (!B && A) || (B && !A) || (!B && !A)
            // ! (A && B && C) :
            //    (!A && !B && !C)
            // || (!A && B && C) || (!A && !B && C) || (!A && B && !C)
            // || (A && !B && C) || (!A && !B && C) || (A && !B && !C)
            // || (A && B && !C) || (!A && B && !C) || (A && !B && !C)

            // We combine all possibilities of rules and themselves negated
            $new_rule = new OrRule;
            $child_operands = $operand->getOperands();

            $current_operand = array_shift($child_operands);
            $current_operand_possibilities = new OrRule([
                new AndRule([
                    $current_operand->copy(),
                ]),
                new AndRule([
                    new NotRule($current_operand->copy())
                ])
            ]);

            // for every remaining operand, we duplicate the already made
            // combinations and add on half of them !$next_operand
            // and $next_operand on the other half
            while ($next_operand = array_shift($child_operands)) {

                $next_operand_possibilities = $current_operand_possibilities->copy();

                $tmp = [];
                foreach ($current_operand_possibilities->getOperands() as $current_operand_possibility) {
                    $tmp[] = $current_operand_possibility->addOperand( $next_operand->copy() );
                }
                $current_operand_possibilities->setOperands($tmp);

                foreach ($next_operand_possibilities->getOperands() as $next_operand_possibility) {
                    $current_operand_possibilities->addOperand(
                        $next_operand_possibility->addOperand( new NotRule($next_operand->copy()) )
                    );
                }
            }

            // We remove the only possibility where no rule is negated
            $combinations = $current_operand_possibilities->getOperands();
            array_shift($combinations); // The first rule contains no negation
            $new_rule->setOperands( $combinations )
                // ->dump(true)
                ;
        }
        elseif ($operand instanceof OrRule) {
            // ! (A || B) : !A && !B
            // ! (A || B || C || D) : !A && !B && !C && !D
            $new_rule = new AndRule;
            foreach ($operand->getOperands() as $operand)
                $new_rule->addOperand( new NotRule($operand->copy()) );
        }
        else {
            throw new \LogicException(
                'Removing NotRule(' . var_export($operand, true) . ') '
                . ' not implemented'
            );
        }

        return $new_rule;
    }

    /**
     * Not rules can only have one operand.
     *
     * @return $this
     */
    public function unifyAtomicOperands($unifyDifferentOperands = true)
    {
        if (!$this->isSimplificationAllowed())
            return $this;

        $this->moveSimplificationStepForward( self::unify_atomic_operands );
        return $this;
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

        if (!$options['show_instance'] && isset($this->cache['array']))
            return $this->cache['array'];

        $array = [
            $options['show_instance'] ? $this->getInstanceId() : self::operator,
            $this->getOperandAt(0) ? $this->getOperandAt(0)->toArray($options) : false
        ];

        if (!$options['show_instance'])
            return $this->cache['array'] = $array;
        else
            return $array;
    }

    /**
     */
    public function toString(array $options=[])
    {
        $operator = self::operator;
        if (!$this->operands) {
            return "['{$operator}']";
        }

        $indent_unit = isset($options['indent_unit']) ? $options['indent_unit'] : '';
        $line_break  = $indent_unit ? "\n" : '';

        $out = "['{$operator}',"
            . $line_break
            . ($indent_unit ? : ' ')
            . str_replace($line_break, $line_break.$indent_unit, $this->getOperandAt(0)->toString($options))
            . ','
            . $line_break
            . ']'
            ;

        return $out;
    }

    /**
     * This method is meant to be used during simplification that would
     * need to change the class of the current instance by a normal one.
     *
     * @return OrRule The current instance (of or or subclass) or a new OrRule
     */
    public function setOperandsOrReplaceByOperation($new_operands)
    {
        if (count($new_operands) > 1) {
            foreach ($new_operands as &$new_operand) {
                if ($new_operand instanceof AbstractRule)
                    $new_operand = $new_operand->toString();
            }

            throw new \InvalidArgumentException(
                "Negations can handle only one operand instead of: "
                .var_export($new_operands, true)
            );
        }

        $new_operand = reset($new_operands);

        if ($new_operand instanceof NotRule) {
            $operands = $new_operand->getOperands();
            return reset( $operands );
        }

        try {
            // Don't use addOperand here to allow inheritance for optimizations (e.g. NotInRule)
            return $this->setOperands( $new_operands );
        }
        catch (\LogicException $e) {
            return new NotRule( $new_operand );
        }
    }

    /**/
}
