<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Position
 * @package Modules\Intranet\Models
 * @version June 17, 2020, 4:18 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property string $nombre
 * @property string $descripcion
 */
class Position extends Model
{
    use SoftDeletes;

    public $table = 'cargos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users()
    {
        return $this->hasMany(\Modules\Intranet\Models\User::class, 'id_cargo');
    }
}
