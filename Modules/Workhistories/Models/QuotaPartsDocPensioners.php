<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaPartsDocPensioners
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 5:08 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property Modules\Workhistories\Models\WorkHistoriesPConfigDocument $configDocuments
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesCpPDocumentsNews
 * @property string $type_document
 * @property string $description
 * @property string $state
 * @property string $url_document
 * @property integer $sheet
 * @property integer $config_documents_id
 * @property integer $cp_pensionados_id
 */
class QuotaPartsDocPensioners extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp_p_documents';
    
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
        'cp_pensionados_id'
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
        'cp_pensionados_id' => 'integer'
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
    public function cpPensionados()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesCpPensionado::class, 'cp_pensionados_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function configDocuments()
    {
        return $this->belongsTo(\Modules\Workhistories\Models\configDocPensioners::class, 'config_documents_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesCpPDocumentsNews()
    {
        return $this->hasMany(\Modules\Workhistories\Models\WorkHistoriesCpPDocumentsNews::class, 'cp_p_documents_id');
    }
}
