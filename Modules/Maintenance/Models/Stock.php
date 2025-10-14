<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class Stock
 * @package Modules\Maintenance\Models
 * @version February 5, 2024, 10:54 am -05
 *
 * @property Modules\Maintenance\Models\MantSnRequestNeed $idSolicitudNecesidad
 * @property integer $id_solicitud_necesidad
 * @property string $codigo
 * @property string $articulo
 * @property string $grupo
 * @property integer $cantidad
 * @property integer $costo_unitario
 * @property integer $total
 */
class Stock extends Model {
    use SoftDeletes;

    public $table = 'mant_stock';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['total_value','unit_cost','iva_bd'];
    
    public $fillable = [
        'id_solicitud_necesidad',
        'codigo',
        'articulo',
        'grupo',
        'cantidad',
        'unidad_medida',
        'costo_unitario',
        'iva',
        'total',
        'bodega'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_solicitud_necesidad' => 'integer',
        'codigo' => 'string',
        'articulo' => 'string',
        'grupo' => 'string',
        'cantidad' => 'float',
        'costo_unitario' => 'float',
        'total' => 'float',
        'bodega' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_solicitud_necesidad' => 'required',
        'codigo' => 'nullable|string|max:100',
        'articulo' => 'nullable|string|max:100',
        'grupo' => 'nullable|string|max:50',
        'cantidad' => 'nullable|integer',
        'costo_unitario' => 'nullable',
        'total' => 'nullable',
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
    public function idSolicitudNecesidad() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantSnRequestNeed::class, 'id_solicitud_necesidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function StockHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\StockHistory::class, 'stock_id')->latest();
    }

    public function getTotalValueAttribute(){
        // $totalValue = round(((float)$this->costo_unitario + (float)$this->iva_bd)) * $this->cantidad);

        $totalValue = round(((float)$this->unit_cost) * $this->cantidad);

        return $totalValue;
    }

    public function getUnitCostAttribute(){
        // Si no hay cantidad disponible entonces costo unitario debe ser 0
        if($this->cantidad == 0){
            return 0;
        }

        // $unitCost = ceil((float)$this->costo_unitario + (float)$this->iva_bd);

        $unitCost = ceil((float)$this->costo_unitario);
        return $unitCost;
    }
    public function getIvaBdAttribute(){
        if($this->cantidad == 0){
            return 0;
        }

        $PERCENTAGE_PRODUCT_IVA = ((int)$this->iva == 0) ? 0 : .19;
        $iva = (int)$this->costo_unitario * $PERCENTAGE_PRODUCT_IVA;
        return $iva;
    }
}
