<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class RequestNeedHistory
 * @package Modules\Maintenance\Models
 * @version November 29, 2023, 2:55 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnRequest $mantSnRequest
 * @property Modules\Maintenance\Models\User $users
 * @property string $users_nombre
 * @property string $observacion
 * @property string $estado
 * @property integer $users_id
 * @property integer $mant_sn_request_id
 */
class RequestNeedHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_request_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_nombre',
        'observacion',
        'estado',
        'users_id',
        'mant_sn_request_id',
        'accion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_nombre' => 'string',
        'observacion' => 'string',
        'estado' => 'string',
        'users_id' => 'integer',
        'mant_sn_request_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_nombre' => 'nullable|string|max:100',
        'observacion' => 'nullable|string',
        'estado' => 'nullable|string|max:45',
        'users_id' => 'required',
        'mant_sn_request_id' => 'required'
    ];
    
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantSnRequest() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnRequest::class, 'mant_sn_request_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
