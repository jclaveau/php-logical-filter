<?php
/**
 * Trait_RuleWithOptions
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;
use       JClaveau\LogicalFilter\LogicalFilter;

/**
 * Options are defined in three levels:
 * + Current instance
 * + Context (e.g. the current running simplification)
 * + Default global value handled by LogicalFilter statically
 */
trait Trait_RuleWithOptions
{
    /** @var array $options */
    protected $options = [];

    /**
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $this->options[$name] = $value;
        }
    }

    /**
     */
    public function getOption($name, array $contextual_options=[])
    {
        $options = $this->getOptions($contextual_options);

        return isset($options[$name]) ? $options[$name] : null;
    }

    /**
     * @param $contextual_options
     */
    public function getOptions(array $contextual_options=[])
    {
        $default_options = LogicalFilter::getDefaultOptions();

        $options = $default_options;

        foreach ($this->options as $name => $value) {
            $options[$name] = $value;
        }

        foreach ($contextual_options as $name => $value) {
            $options[$name] = $value;
        }

        return $options;
    }

    /**/
}
