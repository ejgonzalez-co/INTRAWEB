<?php

namespace Modules\Workhistories\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QuotaParts
 * @package Modules\Workhistories\Models
 * @version December 10, 2020, 5:26 pm -05
 *
 * @property Modules\Workhistories\Models\WorkHistoriesCpPensionado $cpPensionados
 * @property Modules\Workhistories\Models\WorkHistoriesPConfigDocument $configDocuments
 * @property string $name_company
 * @property integer $time_work
 * @property string $observation
 * @property string $url_document
 * @property integer $config_documents_id
 * @property integer $cp_pensionados_id
 */
class QuotaParts extends Model
{
    use SoftDeletes;

    public $table = 'work_histories_cp';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_company',
        'time_work',
        'observation',
        'url_document',
        'cp_pensionados_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_company' => 'string',
        'time_work' => 'integer',
        'observation' => 'string',
        'url_document' => 'string',
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
        return $this->belongsTo(\Modules\Workhistories\Models\WorkHistoriesPConfigDocument::class, 'config_documents_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function quotaPartsHistory()
    {
        return $this->hasMany(\Modules\Workhistories\Models\QuotaPartsHistory::class, 'work_histories_cp_id');
    }
    
}
