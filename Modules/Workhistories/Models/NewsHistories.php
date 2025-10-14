<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NewsHistories
 * @package Modules\Workhistories\Models
 * @version November 9, 2020, 3:58 pm -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistory $workHistories
 * @property Modules\Workhistories\Models\WorkHistoriesDocument $workHistoriesDocuments
 * @property string $new
 * @property string $type_document
 * @property string $users_name
 * @property integer $work_histories_documents_id
 * @property integer $work_histories_id
 * @property integer $users_id
 */
class NewsHistories extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_documents_news_user';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'users_name',
        'work_histories_id',
        'users_id'
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
        'users_name' => 'string',
        'work_histories_id' => 'integer',
        'users_id' => 'integer'
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
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }

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
