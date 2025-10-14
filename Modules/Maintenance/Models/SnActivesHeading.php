<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SnActivesHeading solicitudes de necesidades
 * Contiene la relacion entre activos y rubros
 * @package Modules\Maintenance\Models
 */
class SnActivesHeading extends Model
{
    use SoftDeletes;

    public $table = 'mant_sn_actives_heading';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'activo_tipo',
        'activo_id',
        'rubro_id',
        'centro_costo_id',
        'rubro_codigo',
        'centro_costo_codigo'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachinery()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentMachinery::class, 'activo_id');
    }

    // public function rubro(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(\Modules\Intranet\Models\Dependency::class, 'rubro_id');
    // }

    // public function centrocosto(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(\Modules\PQRS\Models\PQREjeTematico::class, 'centro_costo_id');
    // }

    
}
