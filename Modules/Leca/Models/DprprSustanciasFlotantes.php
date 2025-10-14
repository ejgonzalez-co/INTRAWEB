<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DprprSustancias
 * @package Modules\Leca\Models
 * @version August 3, 2022, 5:46 pm -05
 *
 * @property Modules\Leca\Models\LcEnsayoSustancias $lcEnsayoSustancias
 * @property string $real
 * @property string $add1
 * @property string $add2
 * @property string $tipo
 * @property string $consecutivo
 * @property string $consecutivo_ensayo
 * @property string $user_name
 * @property string $resultado
 * @property string $resultado_completo
 * @property string $user_id
 * @property string $volumen_adicionado
 * @property string $volumen_muestra
 * @property string $concentracion
 * @property string $id_ensayo
 * @property string $id_muestra
 * @property integer $lc_ensayo_sustancias_id
 */
class DprprSustancias extends Model
{
    use SoftDeletes;

    public $table = 'lc_dpr_pr_sustancias';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'real',
        'add1',
        'add2',
        'tipo',
        'consecutivo',
        'consecutivo_ensayo',
        'user_name',
        'resultado',
        'resultado_completo',
        'user_id',
        'volumen_adicionado',
        'volumen_muestra',
        'concentracion',
        'id_ensayo',
        'id_muestra',
        'lc_ensayo_sustancias_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'real' => 'string',
        'add1' => 'float',
        'add2' => 'float',
        'tipo' => 'string',
        'consecutivo' => 'string',
        'consecutivo_ensayo' => 'string',
        'user_name' => 'string',
        'resultado' => 'float',
        'resultado_completo' => 'string',
        'user_id' => 'string',
        'volumen_adicionado' => 'string',
        'volumen_muestra' => 'string',
        'concentracion' => 'string',
        'id_ensayo' => 'string',
        'id_muestra' => 'string',
        'lc_ensayo_sustancias_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'real' => 'nullable|string|max:120',
        'add1' => 'nullable|string|max:120',
        'add2' => 'nullable|string|max:120',
        'tipo' => 'nullable|string|max:120',
        'consecutivo' => 'nullable|string|max:120',
        'consecutivo_ensayo' => 'nullable|string|max:120',
        'user_name' => 'nullable|string|max:120',
        'resultado' => 'nullable|string|max:120',
        'resultado_completo' => 'nullable|string|max:120',
        'user_id' => 'nullable|string|max:120',
        'volumen_adicionado' => 'nullable|string|max:120',
        'volumen_muestra' => 'nullable|string|max:120',
        'concentracion' => 'nullable|string|max:120',
        'id_ensayo' => 'nullable|string|max:255',
        'id_muestra' => 'nullable|string|max:120',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'lc_ensayo_sustancias_id' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcEnsayoSustancias()
    {
        return $this->belongsTo(\Modules\Leca\Models\LcEnsayoSustancias::class, 'lc_ensayo_sustancias_id');
    }
}