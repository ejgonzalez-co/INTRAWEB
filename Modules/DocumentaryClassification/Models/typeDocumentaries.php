<?php

namespace Modules\DocumentaryClassification\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class typeDocumentaries
 * @package Modules\DocumentaryClassification\Models
 * @version March 31, 2023, 3:02 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $cdTypeDocumentariesHasClSeriesSubseries
 * @property string $name
 * @property string $description
 */
class typeDocumentaries extends Model
{
        use SoftDeletes;

    public $table = 'cd_type_documentaries';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
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
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cdTypeDocumentariesHasClSeriesSubseries() {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CdTypeDocumentariesHasClSeriesSubseries::class, 'id_type_documentaries');
    }
}
