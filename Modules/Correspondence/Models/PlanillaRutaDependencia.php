<?php

namespace Modules\Correspondence\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaRutaDependencia extends Model
{
    public $table = 'correspondence_planilla_ruta_has_dependencias';

    public $fillable = [
        'correspondence_planilla_ruta_id',
        'dependencias_id'
    ];

    protected $casts = [
        
    ];

    public static array $rules = [
        'correspondence_planilla_ruta_id' => 'required',
        'dependencias_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function correspondencePlanillaRuta(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Correspondence\Models\CorrespondencePlanillaRutum::class, 'correspondence_planilla_ruta_id');
    }

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }
}
