<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Headquarter
 * @package Modules\Intranet\Models
 * @version June 17, 2020, 4:36 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection $dependencias
 * @property string $nombre
 * @property string $descripcion
 * @property string $codigo
 */
class Headquarter extends Model
{
    use SoftDeletes;

    public $table = 'sedes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'nombre',
        'descripcion',
        'codigo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nombre' => 'string',
        'descripcion' => 'string',
        'codigo' => 'string'
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
    public function dependencias()
    {
        return $this->hasMany(\Modules\Intranet\Models\Dependencia::class, 'id_sede');
    }
}
