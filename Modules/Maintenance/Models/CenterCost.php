<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CenterCost
 * @package Modules\Maintenance\Models
 * @version August 13, 2021, 2:28 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantAdministrationCostItems
 * @property string $name
 * @property string $code_center
 */
class CenterCost extends Model
{
        use SoftDeletes;

    public $table = 'mant_center_cost';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'code_center'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code_center' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:255',
        'code_center' => 'nullable|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantAdministrationCostItems() {
        return $this->hasMany(\Modules\Maintenance\Models\MantAdministrationCostItem::class, 'mant_center_cost_id');
    }
}
