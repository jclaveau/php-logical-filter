<?php
namespace JClaveau\LogicalFilter\Rule;
use       JClaveau\LogicalFilter\FilteredValue;
use       JClaveau\LogicalFilter\FilteredKey;

/**
 * Atomic rules are those who cannot be simplified (so already are):
 * + null
 * + not null
 * + equal
 * + above
 * + below
 * Atomic rules are related to a field.
 */
abstract class AbstractAtomicRule extends AbstractRule
{
    use Trait_RuleWithField;

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
            if ( ! isset($options[ $default_option ])) {
                $options[ $default_option ] = $default_value;
            }
        }


        if ( ! $options['show_instance'] && ! empty($this->cache['array'])) {
            return $this->cache['array'];
        }

        $class = get_class($this);

        $array = [
            $this->getField(),
            $options['show_instance'] ? $this->getInstanceId() : $class::operator,
            $this->getValues(),
        ];

        if ( ! $options['show_instance']) {
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
        if ( ! empty($this->cache['string'])) {
            return $this->cache['string'];
        }

        $class    = get_class($this);
        $operator = $class::operator;

        $stringified_value = var_export($this->getValues(), true);

        $field = $this->getField();

        if ($field instanceof FilteredValue || $field instanceof FilteredKey) {
            $field = "$field";
        }
        elseif ($field instanceof \Closure) {
            throw new \Exception("Closures dump not implemented");
        }
        else {
            $field = "'$field'";
        }

        return $this->cache['string'] = "[$field, '$operator', $stringified_value]";
    }

    /**/
}
