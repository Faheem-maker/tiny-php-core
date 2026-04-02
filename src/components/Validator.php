<?php

namespace framework\components;

use framework\Component;
use framework\models\attributes\Email;
use framework\models\attributes\Required;

class Validator extends Component
{
    protected $registry;

    public function __construct()
    {
        $this->registry = [
            'required' => Required::class,
            'email' => Email::class,
        ];
    }

    public function addValidator($name, $class)
    {
        $this->registry[$name] = $class;
    }

    public function validate($data, $rules)
    {
        // Resolve registry
        foreach ($rules as $field => $validators) {
            if (is_string($validators)) {
                $validators = explode('|', $validators);
                $rules[$field] = $validators;
            }
            foreach ($validators as $key => $validator) {
                if (is_string($validator) && isset($this->registry[$validator])) {
                    $rules[$field][$key] = $this->registry[$validator];
                }
            }
        }

        $errors = [];
        foreach ($rules as $field => $validators) {
            if (is_string($validators)) {
                $validators = explode('|', $validators);
            }
            foreach ($validators as $validator) {
                if (is_callable($validator)) {
                    $result = $validator($data->$field ?? null);
                    if (!empty($result)) {
                        $errors[$field] = $result;
                        break;
                    }
                } elseif ($validator instanceof \framework\contracts\Validator) {
                    if (!$validator->validate($data->$field ?? null)) {
                        $errors[$field] = $validator->message();
                        break;
                    }
                } elseif (is_string($validator)) {
                    $validatorInstance = new $validator();
                    if (!$validatorInstance->validate($data->$field ?? null)) {
                        $errors[$field] = $validatorInstance->message;
                        break;
                    }
                }
            }
        }
        return $errors;
    }
}