<?php

namespace framework\web\models;

class Model
{
    public $errors = [];

    protected static function hasProperty($name)
    {
        $cls = \get_called_class();
        $reflection = new \ReflectionClass($cls);
        return $reflection->hasProperty($name);
    }

    public static function basename()
    {
        $cls = \get_called_class();
        $cls = explode('\\', $cls);
        return end($cls);
    }

    public static function from($data): static
    {
        $base = static::basename();
        if (is_array($data) && isset($data[$base])) {
            $data = $data[$base];
        }
        $meta = static::getMetaData();
        $cls = \get_called_class();
        $model = new $cls();
        foreach ($meta as $key => $info) {
            if (empty($data[$key]))
                continue;
            if ($info['type'] == 'DateTime') {
                $model->{$key} = new \DateTime($data[$key]);
            } else {
                $model->{$key} = $data[$key] ?? null;
            }
        }
        return $model;
    }

    public static function getMetaData()
    {
        $cls = \get_called_class();
        $reflection = new \ReflectionClass($cls);
        $properties = $reflection->getProperties();
        $metaData = [];

        foreach ($properties as $property) {
            // Only proceed if the property was defined in the current class ($cls)
            if ($property->getDeclaringClass()->getName() === $cls) {
                $metaData[$property->getName()] = [
                    'name' => $property->getName(),
                    'type' => (string) $property->getType(),
                    'initialized' => function ($instance) use ($property) {
                        return $property->isInitialized($instance);
                    },
                    'attributes' => $property->getAttributes()
                ];
            }
        }

        return $metaData;
    }

    /**
     * Instance Methods
     */
    public function rules()
    {
        return [];
    }

    public function validate()
    {
        $metaData = self::getMetaData();
        $rules = self::rules();

        foreach ($metaData as $property => $data) {
            foreach ($data['attributes'] as $attribute) {
                $instance = $attribute->newInstance();
                $rules[$property][] = $instance;
            }
        }

        $this->errors = app()->validator->validate($this, $rules);
        return empty($this->errors);
    }

    public function errors($name = '')
    {
        if ($name) {
            return $this->errors[$name] ?? '';
        }
        return $this->errors;
    }

    public function error($name, $message)
    {
        $this->errors[$name] = $message;
    }

    public static function label($property)
    {
        $metaData = self::getMetaData();
        if (isset($metaData[$property])) {
            foreach ($metaData[$property]['attributes'] as $attribute) {
                $instance = $attribute->newInstance();
                if (method_exists($instance, 'label')) {
                    return $instance->label();
                }
            }
        }
        return ucfirst($property);
    }
}