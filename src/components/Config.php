<?php

namespace framework\components;

use framework\utils\ConfigContainer;
use framework\Component;

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

    public function has(string $key): bool
    {
        return $this->container->has($key);
    }

    public function all(): array
    {
        return $this->container->all();
    }

    public function load(array $config, bool $override = false): void
    {
        $this->container->load($config, $override);
    }
}
