<?php

namespace Modules\Intranet\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Dependency
 * @package Modules\Intranet\Models
 * @version June 17, 2020, 4:30 pm UTC
 *
 * @property \Modules\Intranet\Models\Sede $idSede
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property integer $id_sede
 * @property string $codigo
 * @property string $nombre
 * @property string $codigo_oficina_productora
 */
class DependenciasRelacionadas extends Model
{
    use SoftDeletes;

    public $table = 'dependencias_relacionadas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'dependencias_id',
        'id_dependencia_relacionada',
        'codigo',
        'nombre'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_sede' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'codigo_oficina_productora' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_sede' => 'required',
        'nombre' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function headquarters()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Headquarter::class, 'id_sede');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users()
    {
        return $this->hasMany(\Modules\Intranet\Models\User::class, 'id_dependencia');
    }
}
