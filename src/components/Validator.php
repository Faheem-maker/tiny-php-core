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
        $errors = [];
        foreach ($rules as $field => $validators) {
            if (is_string($validators)) {
                $validators = explode('|', $validators);
            }
            foreach ($validators as $validator) {
                if ($validator instanceof \framework\contracts\Validator) {
                    if (!$validator->validate($data->$field ?? null)) {
                        $errors[$field] = $validator->message();
                        break;
                    }
                } elseif (is_string($validator) && isset($this->registry[$validator])) {
                    $validatorClass = $this->registry[$validator];
                    $validatorInstance = new $validatorClass();
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