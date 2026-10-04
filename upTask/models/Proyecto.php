<?php

namespace Model;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Proyecto extends ActiveRecord
{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = [
        'id',
        'proyecto',
        'url',
        'propietario_id',
    ];

    function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietario_id = $args['propietario_id'] ?? null;
    }

    public function validar(): array
    {
        if (!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }
        return self::$alertas;
    }

}