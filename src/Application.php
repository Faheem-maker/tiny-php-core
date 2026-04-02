<?php

namespace framework;

use framework\contracts\ApplicationInterface;
use framework\contracts\ComponentInterface;
use framework\contracts\ExtensionInterface;

/**
 * Base application class
 * 
 * @property-read \framework\components\Config $config
 * @property-read \framework\components\PathManager $path
 * @property-read \framework\components\Logger $logger
 * @property-read \framework\components\Validator $validator
 */
abstract class Application implements ApplicationInterface
{
    protected static ?Application $instance = null;

    /**
     * Container for registered components
     * @var array<string, ComponentInterface|string|callable>
     */
    protected array $components = [];

    /**
     * List of installed extensions
     * @var array<string, ExtensionInterface>
     */
    protected array $extensions = [];

    public function init()
    {
        foreach ($this->components as $component) {
            if ($component instanceof ComponentInterface) {
                $component->init();
            }
        }

        foreach ($this->extensions as $extension) {
            $extension->init();
            $extension->bootstrap($this);
        }
    }

    public abstract function run();

    /**
     * Short alias for getInstance()
     */
    public static function get(): Application
    {
        return static::$instance;
    }

    /**
     * Register a component in the container
     */
    public function registerComponent(string $name, $component): void
    {
        $this->components[$name] = $component;
    }

    public function registerExtension(string $name, ExtensionInterface $extension): void
    {
        $this->components[$name] = $extension;
    }

    /**
     * Magic getter for components
     */
    public function __get(string $name)
    {
        $com = $this->components[$name];

        if (is_string($com)) {
            $this->components[$name] = $this->di->make($com);

            $this->components[$name]->init();
        } else if (is_callable($com)) {
            $this->components[$name] = $com($this);
            $this->components[$name]->init();
        }

        return $this->components[$name] ?? null;
    }

    /**
     * Check if component exists
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->components);
    }
}