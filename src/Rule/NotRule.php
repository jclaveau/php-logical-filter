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

        // negation has only one operand
        // TODO support multiple operands for not adding implicitely an
        // AndRule as operand
        $this->operands = [$operand];
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

        $operand = $this->operands[0];

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
            $new_rule = $operand->getOperands()[0];
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
        $this->moveSimplificationStepForward( self::unify_atomic_operands );
        return $this;
    }

    /**
     */
    public function toArray($debug=false)
    {
        return [
            $debug ? $this->getInstanceId() : self::operator,
            $this->operands[0]->toArray($debug)
        ];
    }

    /**/
}
