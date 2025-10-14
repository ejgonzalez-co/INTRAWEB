<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsPensioners
 * @package Modules\Workhistories\Models
 * @version December 4, 2020, 3:18 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property Modules\Workhistories\Models\WorkHistoriesPConfigDocument $workHistoriesPConfigDocuments
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPDocumentsNewsUsers
 * @property string $type_document
 * @property string $description
 * @property string $state
 * @property string $url_document
 * @property integer $sheet
 * @property integer $work_histories_p_id
 * @property integer $config_documents_id
 */
class DocumentsPensioners extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_document',
        'description',
        'state',
        'url_document',
        'sheet',
        'work_histories_p_id',
        'config_documents_id',
        'document_date'

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
        'sheet' => 'integer'
        ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type_document' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'state' => 'nullable|string|max:45',
        'sheet' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesP()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesP::class, 'work_histories_p_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesPConfigDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\configDocPensioners::class, 'config_documents_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesPDocumentsNewsUsers()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesPDocumentsNewsUser::class, 'work_histories_p_documents_id');
    }

       /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesConfigDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\configDocPensioners::class, 'config_documents_id');
    }

}
