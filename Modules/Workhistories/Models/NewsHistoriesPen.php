<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NewsHistoriesPen
 * @package Modules\Workhistories\Models
 * @version December 5, 2020, 11:34 am -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistoriesP $workHistoriesP
 * @property Modules\Workhistories\Models\WorkHistoriesPDocument $documents
 * @property string $new
 * @property string $type_document
 * @property string $users_name
 * @property integer $work_histories_p_id
 * @property integer $users_id
 * @property integer $documents_id
 */
class NewsHistoriesPen extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_documents_news_user';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'users_name',
        'work_histories_p_id',
        'users_id',
        'documents_id'
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
        'users_id' => 'integer',
        'documents_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'new' => 'nullable|string',
        'type_document' => 'nullable|string|max:255',
        'users_name' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
    public function workHistoriesP()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesP::class, 'work_histories_p_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function documents()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPDocument::class, 'documents_id');
    }
}
