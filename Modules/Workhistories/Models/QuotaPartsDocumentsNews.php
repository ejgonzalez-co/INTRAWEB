<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsDocumentsNews
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 6:06 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCpPDocument $cpPDocuments
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property Modules\Workhistories\Models\User $users
 * @property string $new
 * @property string $type_document
 * @property string $users_name
 * @property integer $users_id
 * @property integer $cp_p_documents_id
 * @property integer $cp_pensionados_id
 */
class QuotaPartsDocumentsNews extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_p_documents_news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'new',
        'type_document',
        'users_name',
        'users_id',
        'cp_p_documents_id',
        'cp_pensionados_id'
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
        'cp_p_documents_id' => 'integer',
        'cp_pensionados_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'cp_p_documents_id' => 'required',
        'cp_pensionados_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cpPDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesCpPDocument::class, 'cp_p_documents_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cpPensionados()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesCpPensionado::class, 'cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\User::class, 'users_id');
    }
}
