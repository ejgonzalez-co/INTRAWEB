<?php

namespace Modules\DocumentaryClassification\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class CriteriosBusquedaValue extends Model
{
    public $table = 'cd_criterios_busqueda_value';

    public $fillable = [
        'cd_inventory_documental_id',
        'texto_criterio',
        'lista_criterio',
        'contenido_criterio',
        'numero_criterio',
        'fecha_criterio'
    ];

    protected $casts = [
        'texto_criterio' => 'string',
        'lista_criterio' => 'string',
        'contenido_criterio' => 'string',
        'numero_criterio' => 'string',
        'fecha_criterio' => 'date'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public static array $rules = [
        'cd_inventory_documental_id' => 'required',
        'texto_criterio' => 'nullable|string|max:150',
        'lista_criterio' => 'nullable|string|max:150',
        'contenido_criterio' => 'nullable|string',
        'numero_criterio' => 'nullable|string|max:150',
        'fecha_criterio' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function InventoryDocumental()
    {
        return $this->belongsTo(\Modules\DocumentaryClassification\Models\InventoryDocumental::class, 'cd_inventory_documental_id');
    }
}
