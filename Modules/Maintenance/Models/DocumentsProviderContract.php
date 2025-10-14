<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsProviderContract
 * @package Modules\Maintenance\Models
 * @version June 3, 2021, 10:04 am -05
 *
 * @property Modules\Maintenance\Models\MantProviderContract $mantProviderContract
 * @property string $name
 * @property string $description
 * @property string $url_document
 * @property integer $mant_provider_contract_id
 */
class DocumentsProviderContract extends Model
{
    use SoftDeletes;

    public $table = 'mant_documents_provider_contract';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'url_document',
        'mant_provider_contract_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url_document' => 'string',
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
