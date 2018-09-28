<?php
/**
 * Value
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter;

/**
 */
class Value implements \JsonSerializable
{
    protected $stack = [];

    public function __call($method, array $arguments)
    {
        $this->stack[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }

    public static function __callStatic($method, array $arguments)
    {
        $instance = new self;
        call_user_func_array([$instance, $method], $arguments);

        return $instance;
    }

    /**
     * For implementing JsonSerializable interface.
     *
     * @see https://secure.php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        return $this->stack;
    }

    /**
     */
    public function __toString()
    {
        $string = 'Value';

        $first = true;
        foreach ($this->stack as $call) {
            if ($first) {
                $string .= '::';
                $first = false;
            }
            else {
                $string .= '->';
            }

            $string .= $call['method'].'(';
            $string .= implode(', ', array_map(function($argument) {
                return var_export($argument, true);
            }, $call['arguments']));
            $string .= ')';
        }

        return $string;
    }

    /**
     * Invoking the instance produces the call of the stack
     */
    public function __invoke($value)
    {
        foreach ($this->stack as $call) {
            try {
                $value->{$call['method']}($call['arguments']);
            }
            catch (\Exception $e) {
                // Throw $e with the good stack (usage exception)
                throw $e;
            }
        }

        return $value;
    }

    /**/
}
