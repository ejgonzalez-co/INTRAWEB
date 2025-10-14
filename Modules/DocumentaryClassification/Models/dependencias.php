<?php

namespace Modules\DocumentaryClassification\Models;

use Illuminate\Database\Eloquent\Model;

class dependencias extends Model
{
    public $table = 'dependencias';

    public $fillable = [
        'id_sede',
        'codigo',
        'nombre',
        'codigo_oficina_productora',
        'cf_user_id'
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'codigo_oficina_productora' => 'string'
    ];

    public static array $rules = [
        'id_sede' => 'required',
        'codigo' => 'nullable|string|max:45',
        'nombre' => 'required|string|max:80',
        'codigo_oficina_productora' => 'nullable|string|max:45',
        'cf_user_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function idSede(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\Sede::class, 'id_sede');
    }

    public function cdDependeciasHasCdSeriesSubseries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CdDependeciasHasCdSeriesSubseries::class, 'id_dependencia');
    }

    public function cdInventoryDocumentals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\CdInventoryDocumental::class, 'id_dependencias');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function trdList() {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\dependenciasSerieSubseries::class, 'id_dependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function trdListSeries()
    {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\dependenciasSerieSubseries::class, 'id_dependencia')->with('seriesOsubseries.CriteriosBusqueda')
        ->where('type', 'Serie');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function trdListSeriesExpedientes()
    {
        return $this->hasMany(\Modules\DocumentaryClassification\Models\dependenciasSerieSubseries::class, 'id_dependencia')->with('seriesOsubseries.CriteriosBusquedaExpedientes')
        ->where('type', 'Serie');
    }
}
