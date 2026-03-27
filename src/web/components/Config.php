<?php

namespace framework\web\components;

use framework\utils\ConfigContainer;
use framework\web\Component;

/**
 * Application configuration container
 *
 * @property string $base_dir Absolute path to root directory
 * @property string $base_url The URL of the website
 */
class Config extends Component
{
    protected ConfigContainer $container;

    public function __construct()
    {
        $this->container = new ConfigContainer();
    }

    public function __get(string $name)
    {
        return $this->container->get($name);
    }

    public function __set(string $name, $value): void
    {
        $this->container->set($name, $value);
    }

    public function set(string $key, $value): void
    {
        $this->container->set($key, $value);
    }

    /**
     * Get config value using dot notation
     *
     * Example:
     *   $config->get('db.username');
     */
    public function get(string $key, $default = null)
    {
        return $this->container->get($key, $default);
    }
}
