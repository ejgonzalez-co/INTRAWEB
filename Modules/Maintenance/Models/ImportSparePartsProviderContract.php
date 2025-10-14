<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImportSparePartsProviderContract
 * @package Modules\Maintenance\Models
 * @version May 28, 2021, 3:45 pm -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $description
 * @property string $file_import
 * @property integer $mant_provider_contract_id
 */
class ImportSparePartsProviderContract extends Model
{
    use SoftDeletes;

    public $table = 'mant_import_spare_parts_provider_contract';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'item',
        'description',
        'unit_measure',
        'unit_value',
        'iva',
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
        'item' => 'integer',
        'description' => 'string',
        'unit_measure' => 'string',
        'unit_value' => 'string',
        'iva' => 'string',
        'total_value' => 'string',
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
