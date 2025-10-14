<?php

namespace Modules\DocumentaryClassification\Models;

use Illuminate\Database\Eloquent\Model;

class criteriosBusqueda extends Model
{
    public $table = 'cd_criterios_busqueda';

    public $fillable = [
        'nombre',
        'tipo_campo',
        'texto_ayuda',
        'requerido',
        'opciones'
    ];



    protected $casts = [
        'nombre' => 'string',
        'tipo_campo' => 'string',
        'texto_ayuda' => 'string',
        'requerido' => 'string',
        'opciones' => 'string'
    ];

    public static array $rules = [
        'nombre' => 'nullable|string|max:30',
        'tipo_campo' => 'nullable|string|max:30',
        'texto_ayuda' => 'nullable|string|max:150',
        'requerido' => 'nullable|string|max:255',
        'opciones' => 'nullable|string',
        // 'created_at' => 'nullable',
        // 'updated_at' => 'nullable',
        // 'deleted_at' => 'nullable'
    ];

    public function SeriesSubseries()
    {
        return $this->belongsToMany(
            \Modules\DocumentaryClassification\Models\seriesSubSeries::class,
            'cd_criterios_busqueda_has_cd_series_subseries',
            'cd_criterios_busqueda_id',
            'cd_series_subseries_id'
        )->select('cd_series_subseries.*', 'cd_criterios_busqueda_has_cd_series_subseries.cd_type_documentaries');
    }
}
