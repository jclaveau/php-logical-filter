<?php
/**
 * Trait_RuleWithCache
 *
 * @package php-logical-filter
 * @author  Jean Claveau
 */
namespace JClaveau\LogicalFilter\Rule;

trait Trait_RuleWithCache
{
    /** @var array $cache */
    protected $cache = [
        'array'       => null,  // filled by toArray()
        'string'      => null,  // filled by toString()
        'semantic_id' => null,  // filled by getSemanticId()
    ];

    /** @var array $static_cache */
    protected static $static_cache = [
        'rules_generation' => [],
    ];

    /**
     */
    public function flushCache()
    {
        $this->cache = [
            'array'       => null,
            'string'      => null,
            'semantic_id' => null,
        ];

        return $this;
    }

    /**
     */
    public static function flushStaticCache()
    {
        static::$static_cache = [
            'rules_generation' => [],
        ];
    }

    /**/
}
