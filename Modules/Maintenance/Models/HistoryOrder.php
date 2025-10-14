<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class HistoryOrder
 * @package Modules\Maintenance\Models
 * @version January 18, 2024, 9:19 am -05
 *
 * @property Modules\Maintenance\Models\MantSnOrder $mantSnOrders
 * @property Modules\Maintenance\Models\User $users
 * @property integer $mant_sn_orders_id
 * @property integer $users_id
 * @property string $nombre_usuario
 * @property string $estado
 * @property string $observacion
 * @property string $accion
 */
class HistoryOrder extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_orders_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_sn_orders_id',
        'users_id',
        'nombre_usuario',
        'estado',
        'observacion',
        'accion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_sn_orders_id' => 'integer',
        'users_id' => 'integer',
        'nombre_usuario' => 'string',
        'estado' => 'string',
        'observacion' => 'string',
        'accion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_sn_orders_id' => 'required',
        'users_id' => 'required',
        'nombre_usuario' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:45',
        'observacion' => 'nullable|string',
        'accion' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
    public function mantSnOrders() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnOrder::class, 'mant_sn_orders_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Maintenance\Models\User::class, 'users_id');
    }
}
