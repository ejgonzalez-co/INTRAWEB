<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AssetType
 * @package Modules\Maintenance\Models
 * @version January 21, 2021, 2:29 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $mantCategories
 * @property string $name
 * @property string $description
 */
class AssetType extends Model
{
    use SoftDeletes;

    public $table = 'mant_asset_type';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'form_type',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'form_type' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantCategories()
    {
        return $this->hasMany(\Modules\Maintenance\Models\Category::class, 'mant_asset_type_id');
    }
}
