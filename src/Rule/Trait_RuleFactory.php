<?php
/**
 * Trait_RuleFactory
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

trait Trait_RuleFactory
{
    /** @var  array $ruleAliases */
    protected static $ruleAliases = [
        '!'    => 'not',
        '='    => 'equal',
        '>'    => 'above',
        '<'    => 'below',
        '><'   => 'between',
        '=><'  => 'between_or_equal_lower',
        '><='  => 'between_or_equal_upper',
        '=><=' => 'between_or_equal_both',
        '<='   => 'below_or_equal',
        '>='   => 'above_or_equal',
        '!='   => 'not_equal',
        'in'   => 'in',
        '!in'  => 'not_in',
    ];

    /**
     * @param  string $english_operator
     * @return string
     */
    public static function findSymbolicOperator($english_operator)
    {
        $association = array_flip( self::$ruleAliases );
        if (isset($association[ $english_operator ])) {
            return $association[ $english_operator ];
        }

        return $english_operator;
    }

    /**
     * @param  string $symbolic_operator
     * @return string
     */
    public static function findEnglishOperator($symbolic_operator)
    {
        $association = self::$ruleAliases;
        if (isset($association[ $symbolic_operator ])) {
            return $association[ $symbolic_operator ];
        }

        return $symbolic_operator;
    }

    /**
     *
     * @param  string $field
     * @param  string $type
     * @param  mixed  $values
     * @param  array  $options
     *
     * @return AbstractRule
     */
    public static function generateSimpleRule($field, $type, $values, array $options=[])
    {
        $cache_key = hash('md4', serialize( func_get_args()) );
        if (isset(self::$static_cache['rules_generation'][$cache_key])) {
            return self::$static_cache['rules_generation'][$cache_key]->copy();
        }

        $ruleClass = self::getRuleClass($type);

        return self::$static_cache['rules_generation'][$cache_key] = new $ruleClass( $field, $values, $options );
    }

    /**
     * @param  string $rule_operator
     *
     * @return string Class corresponding to the given operator
     */
    public static function getRuleClass($rule_operator)
    {
        $english_rule_operator = self::findEnglishOperator($rule_operator);

        $rule_class = __NAMESPACE__
            . '\\'
            . str_replace('_', '', ucwords($english_rule_operator, '_'))
            . 'Rule';

        if ( ! class_exists( $rule_class)) {
            throw new \InvalidArgumentException(
                "The class '$rule_class' corresponding to the  operator "
                ."'$rule_operator' / '$english_rule_operator' cannot be found."
            );
        }

        return $rule_class;
    }

    /**/
}
