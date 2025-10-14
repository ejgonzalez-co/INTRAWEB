<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Heading
 * @package Modules\Maintenance\Models
 * @version August 13, 2021, 2:50 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantAdministrationCostItems
 * @property string $code_heading
 * @property string $name_heading
 */
class Heading extends Model
{
        use SoftDeletes;

    public $table = 'mant_heading';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'code_heading',
        'name_heading'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'code_heading' => 'string',
        'name_heading' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code_heading' => 'nullable|string|max:45',
        'name_heading' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantAdministrationCostItems() {
        return $this->hasMany(\Modules\Maintenance\Models\AdministrationCostItem::class, 'mant_heading_id');
    }
}
