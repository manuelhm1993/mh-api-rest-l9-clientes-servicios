<?php

namespace App\MH\Classes;

use Illuminate\Validation\Rule;

class Helper {
    // Reglas de validación para cada controlador
    private static $rules = [
        'client' => [
            'rules' => [
                'name'    => 'required|string|max:255',
                'email'   => 'required|string|email|unique:clients|max:255',
                'phone'   => 'nullable|string',
                'address' => 'nullable|string',
            ],
        ],
        'service' => [
            'rules' => [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'price'       => 'required|numeric',
            ],
        ],
    ];

    // Devuelve las reglas correspondientes al controlador solicitado y si tiene email lo ignora en una actualización
    public static function getRules(string $class, bool $email = false, $id = null) {
        if(($email) && !is_null($id)) {
            switch($class) {
                case 'client':
                    self::$rules[$class]['rules']['email'] = ['required', 'string', Rule::unique('clients')->ignore($id), 'max:255'];
                    break;
            }
        }

        return self::$rules[$class]['rules'];
    }
}
