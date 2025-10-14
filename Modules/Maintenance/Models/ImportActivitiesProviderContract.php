<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImportActivitiesProviderContract
 * @package Modules\Maintenance\Models
 * @version June 3, 2021, 10:05 am -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property string $name
 * @property string $code
 * @property string $value
 * @property string $description
 * @property string $file_import
 * @property integer $mant_provider_contract_id
 */
class ImportActivitiesProviderContract extends Model
{
    use SoftDeletes;

    public $table = 'mant_import_activities_provider_contract';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'item',
        'description',
        'type',
        'system',
        'unit_measurement',
        'iva',
        'quantity',
        'unit_value',
        'total_value',
        'mant_provider_contract_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'item' => 'string',
        'description' => 'string',
        'iva' => 'string',
        'type' => 'string',
        'system' => 'string',
        'unit_measurement' => 'string',
        
        'mant_provider_contract_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function providerContract()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ProviderContract::class, 'mant_provider_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantProviders()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id')->withTrashed();
    }
}
