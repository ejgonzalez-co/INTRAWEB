<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestNeed
 * @package Modules\SuppliesAdequacies\Models
 * @version November 12, 2024, 8:37 am -05
 *
 * @property \Modules\SuppliesAdequacies\Models\RequestsSuppliesAdjustement $requestsSuppliesAdjustements
 * @property integer $requests_supplies_adjustements_id
 * @property string $need_type
 * @property string $code
 * @property string $unit_measure
 * @property integer $request_quantity
 */
class RequestNeed extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements_needs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'requests_supplies_adjustements_id',
        'need_type',
        'code',
        'unit_measure',
        'request_quantity'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'requests_supplies_adjustements_id' => 'integer',
        'need_type' => 'string',
        'code' => 'string',
        'unit_measure' => 'string',
        'request_quantity' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'requests_supplies_adjustements_id' => 'required',
        'need_type' => 'nullable|string|max:200',
        'code' => 'nullable|string|max:100',
        'unit_measure' => 'nullable|string|max:45',
        'request_quantity' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function requestsSuppliesAdjustements() {
        return $this->belongsTo(\Modules\SuppliesAdequacies\Models\RequestSuppliesAdequacies::class, 'requests_supplies_adjustements_id');
    }
}
