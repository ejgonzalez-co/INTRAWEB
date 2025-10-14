<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AssetCreateAuthorization
 * @package Modules\Maintenance\Models
 * @version February 1, 2021, 11:03 am -05
 *
 * @property Modules\Maintenance\Models\Dependencia $dependencias
 * @property \Illuminate\Database\Eloquent\Collection $mantAuthorizedCategories
 * @property string $responsable
 * @property integer $dependencias_id
 */
class AssetCreateAuthorization extends Model
{
    use SoftDeletes;

    public $table = 'mant_asset_create_authorization';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'responsable',
        'dependencias_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'responsable' => 'integer',
        'dependencias_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dependencias_id' => 'required',
        'responsable' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias()
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usuarios()
    {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'responsable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantAuthorizedCategories()
    {
        return $this->hasMany(\Modules\Maintenance\Models\MantAuthorizedCategory::class, 'asset_authorization_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function authorizedCategoriesModel()
    {
        return $this->hasMany(\Modules\Maintenance\Models\AuthorizedCategories::class, 'asset_authorization_id')->with(["mantAssetType", "mantCategory"]);
    }
}
