<?php

namespace Modules\DocumentaryClassification\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class dependenciasSerieSubseries
 * @package Modules\DocumentaryClassification\Models
 * @version April 16, 2023, 8:37 pm -05
 *
 * @property Modules\DocumentaryClassification\Models\CdDependecia $idDependecias
 * @property Modules\DocumentaryClassification\Models\CdSeriesSubseries $seriesOsubseries
 * @property integer $id_dependencia
 * @property integer $id_series_subseries
 */
class dependenciasSerieSubseries extends Model
{
        // use SoftDeletes;

    public $table = 'cd_dependecias_has_cd_series_subseries';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id_dependencia',
        'id_series_subseries',
        'type',
        'name',
        'no_serieosubserie'
        
        ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_dependencia' => 'integer',
        'id_series_subseries' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_dependencia' => 'nullable|integer',
        'id_series_subseries' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idDependecias() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\dependencias::class, 'id_dependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function seriesOsubseries() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'id_series_subseries')->with('typesList','CriteriosBusqueda');
    }
}
