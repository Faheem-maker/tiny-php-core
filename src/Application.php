<?php

namespace framework;

use framework\contracts\ApplicationInterface;
use framework\contracts\ComponentInterface;
use framework\contracts\ExtensionInterface;
use framework\routing\Router;

/**
 * The base class for all applications.
 * It supports component binding
 * and singleton.
 * 
 * Known Components
 * @property web\components\Config $config Configuration component
 * @property web\components\PathManager $path Path manager component
 * @property web\components\UrlManager $url URL manager component
 * @property web\components\AssetManager $assets Asset manager component
 * @property web\components\WidgetManager $widgets Widget manager component
 * @property web\components\DependencyContainer $di Dependency injection container component
 * @property Router $router
 */
class Application implements ApplicationInterface
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

    public string $route;
    public string $method;

    /**
     * Private constructor to enforce singleton
     */
    private function __construct($route, $method)
    {
        $this->route = $route;
        $this->method = $method;
    }

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

    public function run()
    {
        $executor = new Executor($this->router);

        $executor->execute($this->url->path(), $this->method);
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(string $route, string $method): Application
    {
        if (static::$instance === null) {
            static::$instance = new static($route, $method);
        }

        return static::$instance;
    }

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