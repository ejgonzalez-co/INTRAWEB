<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentOrder
 * @package Modules\Maintenance\Models
 * @version January 17, 2024, 4:53 pm -05
 *
 * @property Modules\Maintenance\Models\MantSnOrder $mantSnOrders
 * @property Modules\Maintenance\Models\User $users
 * @property integer $mant_sn_orders_id
 * @property integer $users_id
 * @property string $nombre
 * @property string $estado
 * @property string $adjunto
 */
class DocumentOrder extends Model {
    use SoftDeletes;

    public $table = 'mant_sn_document_orders';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_sn_orders_id',
        'users_id',
        'nombre',
        'estado',
        'adjunto'
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
        'nombre' => 'string',
        'estado' => 'string',
        'adjunto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
       
    ];

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
