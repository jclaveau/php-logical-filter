<?php
if (!function_exists('spl_object_id')) {
    /**
     * @see https://secure.php.net/manual/en/function.spl-object-id.php
     * This method doesn't exist before PHP 7.2.0
     */
    function spl_object_id($object)
    {
        ob_start();
        var_dump($object); // object(foo)#INSTANCE_ID (0) { }
        return preg_replace('~.+#(\d+).+~s', '$1', ob_get_clean());
    }
}

if (!function_exists('is_iterable')) {
    /**
     * @see https://github.com/symfony/polyfill-php71/commit/36004d119352f4506398032259a08e4ca9607285
     * This method doesn't exist before PHP 7.1.0
     */
    function is_iterable($object)
    {
        return is_array($object) || $object instanceof \Traversable;
    }
}
