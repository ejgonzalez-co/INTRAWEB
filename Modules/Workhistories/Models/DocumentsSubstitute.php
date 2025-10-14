<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsSubstitute
 * @package Modules\Workhistories\Models
 * @version December 7, 2020, 11:07 am -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesPSubstitute $workHistoriesPSubstitute
 * @property Modules\Workhistories\Models\WorkHistoriesPConfigDocument $configDocuments
 * @property string $type_document
 * @property string $description
 * @property string $state
 * @property string $url_document
 * @property integer $sheet
 * @property integer $config_documents_id
 * @property integer $work_histories_p_substitute_id
 */
class DocumentsSubstitute extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_substitute_doc';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_document',
        'description',
        'state',
        'url_document',
        'sheet',
        'config_documents_id',
        'work_histories_p_substitute_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_document' => 'string',
        'description' => 'string',
        'state' => 'string',
        'url_document' => 'string',
        'sheet' => 'integer',
        'config_documents_id' => 'integer',
        'work_histories_p_substitute_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'config_documents_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesPSubstitute()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPSubstitute::class, 'work_histories_p_substitute_id');
    }
       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesConfigDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\configDocPensioners::class, 'config_documents_id');
    }
}
