<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class StockHistory
 * @package Modules\Maintenance\Models
 * @version February 6, 2024, 4:22 am -05
 *
 * @property Modules\Maintenance\Models\MantStock $stock
 * @property integer $stock_id
 * @property integer $usuario_id
 * @property string $usuario_nombre
 * @property string $accion
 * @property integer $cantidad
 */
class StockHistory extends Model {
    use SoftDeletes;

    public $table = 'mant_stock_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'stock_id',
        'usuario_id',
        'usuario_nombre',
        'accion',
        'cantidad'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'stock_id' => 'integer',
        'usuario_id' => 'integer',
        'usuario_nombre' => 'string',
        'accion' => 'string',
        'cantidad' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'stock_id' => 'required',
        'usuario_id' => 'nullable',
        'usuario_nombre' => 'nullable|string|max:100',
        'accion' => 'nullable|string|max:7',
        'cantidad' => 'nullable|integer',
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
    public function stock() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantStock::class, 'stock_id');
    }
}
