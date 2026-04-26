<?php

namespace framework\utils;

class ConfigContainer
{
    private array $config = [];

    /**
     * Set a configuration value.
     *
     * @param string $key The key to set, supports dot notation for nested access (e.g., 'database.username').
     * @param mixed $value The value to set.
     */
    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $current = &$this->config;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    /**
     * Get a configuration value.
     *
     * @param string $key The key to retrieve, supports dot notation for nested access (e.g., 'database.username').
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed The configuration value or the default if not found.
     */
    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $current = $this->config;

        foreach ($keys as $k) {
            if (!isset($current[$k])) {
                return $default;
            }
            $current = $current[$k];
        }

        return $current;
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key The key to check, supports dot notation.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        $keys = explode('.', $key);
        $current = $this->config;

        foreach ($keys as $k) {
            if (!isset($current[$k])) {
                return false;
            }
            $current = $current[$k];
        }

        return true;
    }

    /**
     * Get all configuration data.
     *
     * @return array The entire configuration array.
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * Load configuration from an array.
     *
     * @param array $config The configuration array to load.
     * @param bool $override Whether to override existing config
     */
    public function load(array $config, bool $override = false): void
    {
        if ($override) {
            $this->config = array_merge($this->config, $config);
        } else {
            $this->config = array_merge($config, $this->config);
        }
    }
}