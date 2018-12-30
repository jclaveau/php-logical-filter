<?php
namespace JClaveau\LogicalFilter\Rule;

/**
 * a regexp /^...$/
 *
 * RegexpRule follow the PCRE syntax as PHP and MariaDb.
 *
 * @see https://mariadb.com/kb/en/library/pcre/
 */
class RegexpRule extends AbstractAtomicRule
{
    /** @var string operator */
    const operator = 'regexp';

    /** @var mixed $value */
    protected $pattern;

    /**
     *
     * @param string $field The field to apply the rule on.
     * @param array  $pattern The regular expression to match.
     *
     * @todo  Support Posix?
     */
    public function __construct( $field, $pattern )
    {
        $this->field   = $field;
        $this->pattern = $pattern;
    }

    /**
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->getPattern();
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

    /**
     * Removes the delimiter and write the options in a MariaDB way.
     *
     * @param  string The pattern written in a PHP PCRE way
     * @return string The pattern in a MariaDB syntax
     *
     * @todo   Find more difference between MariaDB and PHP and handle them.
     * @see    https://mariadb.com/kb/en/library/pcre/
     */
    public static function php2mariadbPCRE($php_regexp)
    {
        $delimiter        = substr($php_regexp, 0, 1);
        $quoted_delimiter = preg_quote($delimiter, '#');

        if ( ! preg_match("#^$quoted_delimiter(.*)$quoted_delimiter([^$quoted_delimiter]*)$#", $php_regexp, $matches)) {
            throw new \InvalidArgumentException(
                "The provided PCRE regular expression (with the delimiter '$delimiter') cannot be parsed: "
                .var_export($php_regexp, true)
            );
        }

        $pattern = $matches[1];
        $options = $matches[2];

        return ($options ? "(?$options)" : '') . $pattern;
    }

    /**/
}
