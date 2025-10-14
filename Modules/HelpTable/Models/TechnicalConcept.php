<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TechnicalConcept
 * @package Modules\HelpTable\Models
 * @version April 12, 2023, 4:48 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTechnicalConceptsHistories
 * @property integer $id_staff_member
 * @property integer $technician_id
 * @property integer $reviewer_id
 * @property integer $approver_id
 * @property string $equipment_type
 * @property string $equipment_mark
 * @property string $equipment_model
 * @property string $equipment_serial
 * @property string $inventory_plate
 * @property string $inventory_manager
 * @property string $status
 * @property integer $consecutive
 * @property string $technical_concept
 * @property string $observations
 * @property string $date_issue_concept
 */
class TechnicalConcept extends Model
{
        use SoftDeletes;

    public $table = 'ht_technical_concepts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    protected $appends = ['dependence_name'];
    
    public $fillable = [
        'id_staff_member',
        'technician_id',
        'reviewer_id',
        'approver_id',
        'equipment_type',
        'equipment_mark',
        'equipment_model',
        'equipment_serial',
        'inventory_plate',
        'inventory_manager',
        'status',
        'consecutive',
        'technical_concept',
        'observations',
        'date_issue_concept',
        'expiration_date',
        'url_attachments'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_staff_member' => 'integer',
        'technician_id' => 'integer',
        'reviewer_id' => 'integer',
        'approver_id' => 'integer',
        'equipment_type' => 'string',
        'equipment_mark' => 'string',
        'equipment_model' => 'string',
        'equipment_serial' => 'string',
        'inventory_plate' => 'string',
        'inventory_manager' => 'string',
        'status' => 'string',
        'consecutive' => 'integer',
        'technical_concept' => 'string',
        'observations' => 'string',
        'date_issue_concept' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_staff_member' => 'nullable',
        'technician_id' => 'nullable',
        'reviewer_id' => 'nullable',
        'approver_id' => 'nullable',
        'equipment_type' => 'nullable|string|max:50',
        'equipment_mark' => 'nullable|string|max:50',
        'equipment_model' => 'nullable|string|max:50',
        'equipment_serial' => 'nullable|string|max:30',
        'inventory_plate' => 'nullable|string|max:50',
        'inventory_manager' => 'nullable|string|max:80',
        'status' => 'nullable|string|max:20',
        'consecutive' => 'nullable',
        'technical_concept' => 'nullable|string',
        'observations' => 'nullable|string',
        'date_issue_concept' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function TechnicalConceptsHistory() {
        return $this->hasMany(\Modules\HelpTable\Models\TechnicalConceptsHistory::class, 'ht_technical_concepts_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function StaffMember() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'id_staff_member')->with("dependencies");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function Technicians() {
        return $this->belongsTo(\App\User::class, 'technician_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function Reviewers() {
        return $this->belongsTo(\App\User::class, 'reviewer_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function Approvers() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'approver_id')->with("dependencies");
    }

    public function getDependenceNameAttribute(){
        $user = \Modules\Intranet\Models\User::where('id', $this->id_staff_member)->with("dependencies")->first();
        $dependence_name = !empty($user["dependencies"]) ? $user["dependencies"]["nombre"] : "";
        return $dependence_name;
    }
}
