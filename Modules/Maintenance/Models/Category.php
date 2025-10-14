<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @package Modules\Maintenance\Models
 * @version January 22, 2021, 12:04 pm -05
 *
 * @property Modules\Maintenance\Models\MantAssetType $mantAssetType
 * @property string $name
 * @property integer $mant_asset_type_id
 */
class Category extends Model
{
    use SoftDeletes;

    public $table = 'mant_category';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'mant_asset_type_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'mant_asset_type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mant_asset_type_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantAssetType()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id')->withTrashed();
    }
}
