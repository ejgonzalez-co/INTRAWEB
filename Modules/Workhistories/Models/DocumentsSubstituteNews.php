<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsSubstituteNews
 * @package Modules\Workhistories\Models
 * @version December 9, 2020, 8:49 am -05
 *
 * @property Modules\Workhistories\Models\User $users
 * @property Modules\Workhistories\Models\WorkHistoriesPSubstitute $workHistoriesPSubstitute
 * @property Modules\Workhistories\Models\WorkHistoriesPSubstituteDoc $pSubstituteDoc
 * @property string $new
 * @property string $type_document
 * @property string $users_name
 * @property integer $users_id
 * @property integer $p_substitute_doc_id
 * @property integer $work_histories_p_substitute_id
 */
class DocumentsSubstituteNews extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_p_sub_doc_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
        'p_substitute_doc_id',
        'work_histories_p_substitute_id'
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
        'p_substitute_doc_id' => 'integer',
        'work_histories_p_substitute_id' => 'integer'
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
    public function workHistoriesPSubstitute()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPSubstitute::class, 'work_histories_p_substitute_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pSubstituteDoc()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPSubstituteDoc::class, 'p_substitute_doc_id');
    }
}
