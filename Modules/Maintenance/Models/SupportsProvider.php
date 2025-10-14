<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SupportsProvider
 * @package Modules\Maintenance\Models
 * @version February 22, 2021, 3:22 pm -05
 *
 * @property Modules\Maintenance\Models\MantProvider $mantProviders
 * @property string $name
 * @property string $description
 * @property string $url_document
 * @property integer $mant_providers_id
 */
class SupportsProvider extends Model
{
    use SoftDeletes;

    public $table = 'mant_providers_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'url_document',
        'mant_providers_id'
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
        'mant_providers_id' => 'integer'
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
    public function mantProviders()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\Providers::class, 'mant_providers_id')->withTrashed();
    }
}
