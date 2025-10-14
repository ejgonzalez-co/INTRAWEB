<?php

namespace Modules\PQRS\Models;

use Illuminate\Database\Eloquent\Model;

class PQREjeTematicoDependencias extends Model
{
    public $table = 'pqr_ejetematico_has_dependencias';

    public $fillable = [
        'pqr_eje_tematico_id',
        'dependencias_id'
    ];

    protected $casts = [
        
    ];

    public static array $rules = [
        'pqr_eje_tematico_id' => 'required',
        'dependencias_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function dependencias(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'dependencias_id');
    }

    public function pqrEjeTematico(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\PQRS\Models\PQREjeTematico::class, 'pqr_eje_tematico_id');
    }
}
