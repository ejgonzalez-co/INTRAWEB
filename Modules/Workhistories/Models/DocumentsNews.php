<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsNews
 * @package Modules\Workhistories\Models
 * @version October 28, 2020, 4:35 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property Modules\Workhistories\Models\WorkHistoriesDocument $workHistoriesDocuments
 * @property string $new
 * @property string $type_document
 * @property integer $work_histories_documents_id
 * @property integer $work_histories_id
 */
class DocumentsNews extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_documents_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'work_histories_documents_id',
        'work_histories_id',
        'users_id',
        'users_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'new' => 'string',
        'type_document' => 'string',
        'work_histories_documents_id' => 'integer',
        'work_histories_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'work_histories_documents_id' => 'required',
        'work_histories_id' => 'required'
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
    public function workHistoriesDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesDocument::class, 'work_histories_documents_id');
    }
}
