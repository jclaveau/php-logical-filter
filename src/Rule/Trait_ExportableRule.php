<?php
/**
 * Trait_RuleWithCasts
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

trait Trait_ExportableRule
{
    /**
     * For implementing JsonSerializable interface.
     *
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public final function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public final function __toString()
    {
        return $this->toString();
    }

    /**
     * It would be great if __toBool() existed. Meanwhile we can definitively
     * bind it to hasSolution().
     *
     * @return bool If the rule can have a solution or not.
     */
    public final function toBool()
    {
        return $this->hasSolution();
    }

    /**
     * @return string
     */
    abstract public function toString(array $options=[]);

    /**
     * @return array
     */
    abstract public function toArray(array $options=[]);

    protected $instance_id;

    /**
     * Returns an id describing the instance internally for debug purpose.
     *
     * @see https://secure.php.net/manual/en/function.spl-object-id.php
     *
     * @return string
     */
    public function getInstanceId()
    {
        if ($this->instance_id) {
            return $this->instance_id;
        }

        return $this->instance_id = get_class($this).':'.spl_object_id($this);
    }

    /**
     * Returns an id corresponding to the meaning of the rule.
     *
     * @return string
     */
    final public function getSemanticId()
    {
        if (isset($this->cache['semantic_id'])) {
            return $this->cache['semantic_id'];
        }

        return hash('md4', serialize($this->toArray(['semantic' => true])))  // faster but longer
              .'-'
              .hash('md4', serialize($this->options))
              ;
    }

    /**
     * Dumps the rule with a chained syntax.
     *
     * @param bool  $exit       Default false
     * @param array $options    + callstack_depth=2 The level of the caller to dump
     *                          + mode='string' in 'export' | 'dump' | 'string' | 'xdebug'
     *
     * @return $this
     */
    final public function dump($exit=false, array $options=[])
    {
        $default_options = [
            'callstack_depth' => 2,
            'mode'            => 'string',
            // 'show_instance'   => false,
        ];
        foreach ($default_options as $default_option => &$default_value) {
            if ( ! isset($options[ $default_option ])) {
                $options[ $default_option ] = $default_value;
            }
        }
        extract($options);

        $bt     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $callstack_depth);
        $caller = $bt[ $callstack_depth - 2 ];

        echo "\n" . $caller['file'] . ':' . $caller['line'] . "\n";
        if ('string' == $mode) {
            if ( ! isset($options['indent_unit'])) {
                $options['indent_unit'] = "    ";
            }

            echo($this->toString($options));
        }
        elseif ('dump' == $mode) {
            if ($xdebug_enabled = ini_get('xdebug.overload_var_dump')) {
                ini_set('xdebug.overload_var_dump', 0);
            }

            var_dump($this->toArray($options));

            if ($xdebug_enabled) {
                ini_set('xdebug.overload_var_dump', 1);
            }
        }
        elseif ('xdebug' == $mode) {
            if ( ! function_exists('xdebug_is_enabled')) {
                throw new \RuntimeException("Xdebug is not installed");
            }
            if ( ! xdebug_is_enabled()) {
                throw new \RuntimeException("Xdebug is disabled");
            }

            if ($xdebug_enabled = ini_get('xdebug.overload_var_dump')) {
                ini_set('xdebug.overload_var_dump', 1);
            }

            var_dump($this->toArray($options));

            if ($xdebug_enabled) {
                ini_set('xdebug.overload_var_dump', 0);
            }
        }
        elseif ('export' == $mode) {
            var_export($this->toArray($options));
        }
        else {
            throw new \InvalidArgumentException(
                 "'mode' option must belong to ['string', 'export', 'dump'] "
                ."instead of " . var_export($mode, true)
            );
        }
        echo "\n\n";

        if ($exit) {
            exit;
        }

        return $this;
    }

    /**/
}
