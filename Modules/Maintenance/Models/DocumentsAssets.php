<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentsAssets
 * @package Modules\Maintenance\Models
 * @version March 22, 2021, 11:39 am -05
 *
 * @property Modules\Maintenance\Models\MantResumeMachineryVehiclesYellow $mantResumeMachineryVehiclesYellow
 * @property string $name
 * @property string $description
 * @property string $url_document
 * @property integer $mant_resume_machinery_vehicles_yellow_id
 */
class DocumentsAssets extends Model
{
    use SoftDeletes;

    public $table = 'mant_asset_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'url_document',
        'form_type',
        'images_equipo',
        'mant_resume_machinery_vehicles_yellow_id',
        'mant_resume_equipment_machinery_id',
        'mant_resume_equipment_machinery_leca_id',
        'mant_resume_equipment_leca_id',
        'mant_inventory_metrological_schedule_leca_id'

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url_document' => 'string',
        'form_type' => 'integer',
        'images_equipo' => 'boolean',
        'mant_resume_machinery_vehicles_yellow_id' => 'integer',
        'mant_resume_equipment_machinery_id' => 'integer',
        'mant_resume_equipment_machinery_leca_id' => 'integer',
        'mant_resume_equipment_leca_id' => 'integer',
        'mant_inventory_metrological_schedule_leca_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeMachineryVehiclesYellow()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeMachineryVehiclesYellow::class, 'mant_resume_machinery_vehicles_yellow_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachinery()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentMachinery::class, 'mant_resume_machinery_vehicles_yellow_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentMachineryLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentMachineryLeca::class, 'mant_resume_machinery_vehicles_yellow_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeEquipmentLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeEquipmentLeca::class, 'mant_resume_machinery_vehicles_yellow_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mantResumeInventoryLeca()
    {
        return $this->belongsTo(\Modules\Maintenance\Models\ResumeInventoryLeca::class, 'mant_resume_machinery_vehicles_yellow_id')->withTrashed();
    }
}

