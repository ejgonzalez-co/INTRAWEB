<?php

namespace Modules\NotificacionesIntraweb\Models;

use Illuminate\Database\Eloquent\Model;

class ListadoCorreosCheckeos extends Model
{
    public $table = 'lista_correos_checkeos';

    public $fillable = [
        'email',
        'estado',
        'fecha_verificacion',
        'user_id',
        'user_name'
    ];

    protected $casts = [
        'email' => 'string',
        'estado' => 'string',
        // 'fecha_verificacion' => 'date',
        'user_name' => 'string'
    ];

    public static array $rules = [
        'email' => 'nullable|string|max:16777215',
        'estado' => 'nullable|string|max:45',
        'fecha_verificacion' => 'nullable',
        'user_id' => 'nullable',
        'user_name' => 'nullable|string|max:60',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
