<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AuthorizedCategories
 * @package Modules\Maintenance\Models
 * @version February 3, 2021, 3:28 pm -05
 *
 * @property Modules\Maintenance\Models\MantAssetCreateAuthorization $assetAuthorization
 * @property Modules\Maintenance\Models\MantAssetType $mantAssetType
 * @property Modules\Maintenance\Models\MantCategory $mantCategory
 * @property integer $asset_authorization_id
 * @property integer $mant_asset_type_id
 * @property integer $mant_category_id
 */
class AuthorizedCategories extends Model
{
    use SoftDeletes;

    public $table = 'mant_authorized_categories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'asset_authorization_id',
        'mant_asset_type_id',
        'mant_category_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'asset_authorization_id' => 'integer',
        'mant_asset_type_id' => 'integer',
        'mant_category_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'asset_authorization_id' => 'required',
        'mant_asset_type_id' => 'required',
        'mant_category_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assetAuthorization()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetCreateAuthorization::class, 'asset_authorization_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantAssetType()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\AssetType::class, 'mant_asset_type_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantCategory()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Category::class, 'mant_category_id')->withTrashed();
    }
}
