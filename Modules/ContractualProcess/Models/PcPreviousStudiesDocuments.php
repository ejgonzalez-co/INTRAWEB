<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PcPreviousStudiesDocuments
 * @package Modules\ContractualProcess\Models
 * @version January 14, 2021, 2:19 pm -05
 *
 * @property Modules\ContractualProcess\Models\PcPreviousStudy $pcPreviousStudies
 * @property string $name
 * @property string $description
 * @property string $state
 * @property string $url_document
 * @property integer $sheet
 * @property integer $pc_previous_studies_id
 */
class PcPreviousStudiesDocuments extends Model
{
    use SoftDeletes;

    public $table = 'pc_previous_studies_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'state',
        'url_document',
        'sheet',
        'users_name',
        'pc_previous_studies_id'
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
        'state' => 'string',
        'url_document' => 'string',
        'sheet' => 'integer',
        'pc_previous_studies_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcPreviousStudies()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcPreviousStudy::class, 'pc_previous_studies_id');
    }
}
