<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Documents
 * @package Modules\Workhistories\Models
 * @version October 22, 2020, 2:27 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property Modules\Workhistories\Models\WorkHistoriesConfigDocument $workHistoriesConfigDocuments
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesDocumentsNews
 * @property string $type_document
 * @property string $description
 * @property string $state
 * @property integer $config_documents_id
 * @property integer $work_histories_id
 */
class Documents extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'type_document',
        'description',
        'state',
        'config_documents_id',
        'work_histories_id',
        'url_document',
        'type_document',
        'users_id',
        'sheet',
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
        'config_documents_id' => 'integer',
        'work_histories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        //'config_documents_id' => 'required',
       // 'work_histories_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistories()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistory::class, 'work_histories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workHistoriesConfigDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\ConfigurationDocuments::class, 'config_documents_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesDocumentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesDocumentsNews::class, 'work_histories_documents_id');
    }
}
