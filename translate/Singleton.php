<?php
/**
 * Singleton
 */
class Singleton
{
    /**
     * @var Singleton
     */
    protected static $instance = null;

    protected function __construct()
    {
    }

    /**
     * Retrieve an instance of the given class.
     *
     * @return Singleton
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function __clone()
    {
    }
}

