<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AddingCostItem
 * @package Modules\Maintenance\Models
 * @version December 21, 2023, 8:52 am -05
 *
 * @property Modules\Maintenance\Models\MantAdministrationCostItem $mantAdministrationCostItems
 * @property Modules\Maintenance\Models\MantContractNews $mantContractNews
 * @property integer $mant_contract_news_id
 * @property integer $mant_administration_cost_items_id
 * @property number $value_addition
 */
class AddingCostItem extends Model {
    use SoftDeletes;

    public $table = 'mant_adding_cost_item';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_contract_news_id',
        'mant_administration_cost_items_id',
        'value_addition',
        'code_cost',
        'name',
        'cost_center',
        'cost_center_name',
        'value_item',
        'value_item_old'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_contract_news_id' => 'integer',
        'mant_administration_cost_items_id' => 'integer',
        'value_addition' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'mant_contract_news_id' => 'required',
        // 'mant_administration_cost_items_id' => 'required',
        // 'value_addition' => 'nullable|numeric',
        // 'created_at' => 'nullable',
        // 'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantAdministrationCostItems() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantAdministrationCostItem::class, 'mant_administration_cost_items_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantContractNews() {
        return $this->belongsTo(\Modules\Maintenance\Models\MantContractNews::class, 'mant_contract_news_id');
    }
}
