<?php

namespace Modules\ContractualProcess\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TechnicalSheets
 * @package Modules\ContractualProcess\Models
 * @version January 8, 2021, 8:34 am -05
 *
 * @property Modules\ContractualProcess\Models\City $cities
 * @property Modules\ContractualProcess\Models\Dependencia $dependencias
 * @property Modules\ContractualProcess\Models\PcManagementUnit $pcManagementUnit
 * @property Modules\ContractualProcess\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $pcDirectCausesProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcDirectEffectsProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcIndirectCausesProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcIndirectEffectsProblems
 * @property \Illuminate\Database\Eloquent\Collection $pcOtherPlanningDocuments
 * @property integer $users_id
 * @property integer $dependencias_id
 * @property integer $pc_management_unit_id
 * @property integer $cities_id
 * @property string $code_bppiepa
 * @property string $validity
 * @property string|\Carbon\Carbon $date_presentation
 * @property string|\Carbon\Carbon $update_date
 * @property integer $project_name
 * @property string $responsible_user
 * @property integer $municipal_development_plan
 * @property integer $period
 * @property integer $strategic_line
 * @property integer $program
 * @property integer $subprogram
 * @property integer $sector
 * @property integer $identification_project
 * @property string $description_problem_need
 * @property string $project_description
 * @property string $justification
 * @property string $background
 * @property integer $service_coverage
 * @property integer $number_inhabitants
 * @property string $neighborhood
 * @property string $commune
 */
class TechnicalSheets extends Model
{
    use SoftDeletes;

    public $table = 'pc_technical_sheets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'users_id',
        'dependencias_id',
        'pc_management_unit_id',
        'cities_id',
        'code_bppiepa',
        'validity',
        'date_presentation',
        'update_date',
        'project_name',
        'responsible_user',
        'municipal_development_plan',
        'period',
        'strategic_line',
        'program',
        'subprogram',
        'sector',
        'identification_project',
        'description_problem_need',
        'project_description',
        'justification',
        'background',
        'service_coverage',
        'number_inhabitants',
        'neighborhood',
        'commune'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'dependencias_id' => 'integer',
        'pc_management_unit_id' => 'integer',
        'cities_id' => 'integer',
        'code_bppiepa' => 'string',
        'validity' => 'string',
        'date_presentation' => 'datetime',
        'update_date' => 'datetime',
        'project_name' => 'integer',
        'responsible_user' => 'string',
        'municipal_development_plan' => 'integer',
        'period' => 'integer',
        'strategic_line' => 'integer',
        'program' => 'integer',
        'subprogram' => 'integer',
        'sector' => 'integer',
        'identification_project' => 'integer',
        'description_problem_need' => 'string',
        'project_description' => 'string',
        'justification' => 'string',
        'background' => 'string',
        'service_coverage' => 'integer',
        'number_inhabitants' => 'integer',
        'neighborhood' => 'string',
        'commune' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'users_id' => 'required',
        'dependencias_id' => 'required',
        'pc_management_unit_id' => 'required',
        'cities_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cities()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\City::class, 'cities_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencias()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\Dependencia::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pcManagementUnit()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\PcManagementUnit::class, 'pc_management_unit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\ContractualProcess\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcDirectCausesProblems()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcDirectCausesProblem::class, 'pc_technical_sheets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcDirectEffectsProblems()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcDirectEffectsProblem::class, 'pc_technical_sheets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcIndirectCausesProblems()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcIndirectCausesProblem::class, 'pc_technical_sheets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcIndirectEffectsProblems()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcIndirectEffectsProblem::class, 'pc_technical_sheets_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcOtherPlanningDocuments()
    {
        return $this->hasMany(\Modules\ContractualProcess\Models\PcOtherPlanningDocument::class, 'pc_technical_sheets_id');
    }
}
