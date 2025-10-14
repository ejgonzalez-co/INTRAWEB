<?php

namespace Modules\DocumentaryClassification\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class seriesSubSeries
 * @package Modules\DocumentaryClassification\Models
 * @version March 31, 2023, 3:04 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $cdDependeciasHasCdSeriesSubseries
 * @property \Illuminate\Database\Eloquent\Collection $cdInventoryDocumentals
 * @property \Illuminate\Database\Eloquent\Collection $cdTypeDocumentariesHasClSeriesSubseries
 * @property string $no_serie
 * @property string $name_serie
 * @property string $no_subserie
 * @property string $name_subserie
 * @property string $time_gestion_archives
 * @property string $time_central_file
 * @property string $soport
 * @property string $confidentiality
 * @property boolean $full_conversation
 * @property boolean $select
 * @property boolean $delete
 * @property boolean $medium_tecnology
 * @property boolean $not_transferable_central
 * @property string $description_final
 * @property string $type
 */
class seriesSubSeries extends Model
{
        use SoftDeletes;

    public $table = 'cd_series_subseries';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'no_serie',
        'id_serie',
        'name_serie',
        'no_subserie',
        'name_subserie',
        'time_gestion_archives',
        'time_central_file',
        'soport',
        'confidentiality',
        'enable_expediente',
        'full_conversation',
        'select',
        'delete',
        'medium_tecnology',
        'not_transferable_central',
        'description_final',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'no_serie' => 'string',
        'name_serie' => 'string',
        'no_subserie' => 'string',
        'name_subserie' => 'string',
        'time_gestion_archives' => 'string',
        'time_central_file' => 'string',
        'soport' => 'string',
        'confidentiality' => 'string',
        'enable_expediente' => 'integer',
        'description_final' => 'string',
        'type' => 'string',
        'id_serie' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'no_serie' => 'nullable|string|max:30',
        'name_serie' => 'nullable|string|max:180',
        'no_subserie' => 'nullable|string|max:45',
        'name_subserie' => 'nullable|string|max:180',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cdDependeciasHasCdSeriesSubseries() {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CdDependeciasHasCdSeriesSubseries::class, 'id_series_subseries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cdInventoryDocumentals() {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CdInventoryDocumental::class, 'id_series_subseries');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function typesList() {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\documentarySerieSubseries::class, 'id_series_subseries');
    }

    public function CriteriosBusqueda()
    {
        return $this->belongsToMany(\Modules\DocumentaryClassification\Models\criteriosBusqueda::class, 'cd_criterios_busqueda_has_cd_series_subseries','cd_series_subseries_id','cd_criterios_busqueda_id');
    }

    public function CriteriosBusquedaExpedientes()
    {
        return $this->belongsToMany(\Modules\DocumentaryClassification\Models\criteriosBusqueda::class, 'cd_criterios_busqueda_has_cd_series_subseries','cd_series_subseries_id','cd_criterios_busqueda_id')->wherePivotNull("cd_type_documentaries");
    }

    public function CriteriosBusquedaDocumentos()
    {
        return $this->belongsToMany(\Modules\DocumentaryClassification\Models\criteriosBusqueda::class, 'cd_criterios_busqueda_has_cd_series_subseries','cd_series_subseries_id','cd_criterios_busqueda_id');
    }

    /**
     * Obtiene la relación con la serie padre en caso tal de que la tenga
     */
    public function serie() {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\seriesSubSeries::class, 'id_serie');
    }

    public function tipoDocumental()
{
    return $this->belongsTo(\Modules\DocumentaryClassification\Models\typeDocumentaries::class, 'cd_type_documentaries');
}
}
