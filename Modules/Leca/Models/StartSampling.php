<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StartSampling
 * @package Modules\Leca\Models
 * @version January 3, 2022, 11:48 am -05
 *
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $historyStartSamplings
 * @property \Illuminate\Database\Eloquent\Collection $lcChlorineResidualStandards
 * @property \Illuminate\Database\Eloquent\Collection $lcSampleTakings
 * @property integer $users_id
 * @property string $user_name
 * @property string $vehicle_arrival_time
 * @property string $leca_outlet
 * @property string $time_sample_completion
 * @property string $service_agreement
 * @property string $sample_request
 * @property string $time
 * @property string $name
 * @property string $environmental_conditions
 * @property string $potentiometer_multiparameter
 * @property string $chlorine_residual
 * @property string $turbidimeter
 * @property string $another_test
 * @property string $other_equipment
 * @property string $chlorine_test
 * @property string $factor
 * @property string $residual_color
 * @property string $media_and_DPR
 * @property string $mean_chlorine_value
 * @property string $DPR_chlorine_residual
 * @property string $date_last_ph_adjustment
 * @property string $pending
 * @property string $asymmetry
 * @property string $digital_thermometer
 * @property string $which
 * @property string $arrival_LECA
 * @property string $observations
 * @property string $witness
 * @property string $initial
 * @property string $intermediate
 * @property string $end
 * @property string $standard_ph
 * @property string $chlorine_residual_target
 * @property string $initial_pattern
 */
class StartSampling extends Model {
    use SoftDeletes;

    public $table = 'lc_start_sampling';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'user_name',
        'vehicle_arrival_time',
        'leca_outlet',
        'time_sample_completion',
        'service_agreement',
        'sample_request',
        'time',
        'name',
        'environmental_conditions',
        'potentiometer_multiparameter',
        'chlorine_residual',
        'turbidimeter',
        'another_test',
        'other_equipment',
        'chlorine_test',
        'factor',
        'residual_color',
        'media_and_DPR',
        'mean_chlorine_value',
        'DPR_chlorine_residual',
        'date_last_ph_adjustment',
        'pending',
        'asymmetry',
        'digital_thermometer',
        'which',
        'arrival_LECA',
        'observations',
        'witness',
        'initial',
        'intermediate',
        'end',
        'standard_ph',
        'chlorine_residual_target',
        'initial_pattern',
        'name_person_responsible',
        'name_delivery_conformity',
        'reference_thermohygrometer',
        'reference_multiparameter',
        'reference_photometer',
        'reference_turbidimeter',
        'edition',
        'llegada_temperatura',
        'salida_temperatura',
        'type_customer'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'vehicle_arrival_time' => 'string',
        'leca_outlet' => 'string',
        'time_sample_completion' => 'string',
        'service_agreement' => 'string',
        'sample_request' => 'string',
        'time' => 'string',
        'name' => 'string',
        // 'environmental_conditions' => 'string',
        // 'potentiometer_multiparameter' => 'string',
        // 'chlorine_residual' => 'string',
        // 'turbidimeter' => 'string',
        'another_test' => 'string',
        'other_equipment' => 'string',
        'chlorine_test' => 'string',
        'factor' => 'string',
        'residual_color' => 'string',
        'media_and_DPR' => 'string',
        'mean_chlorine_value' => 'string',
        'DPR_chlorine_residual' => 'string',
        'date_last_ph_adjustment' => 'string',
        'pending' => 'string',
        'asymmetry' => 'string',
        'digital_thermometer' => 'string',
        'which' => 'string',
        'arrival_LECA' => 'string',
        'observations' => 'string',
        'witness' => 'string',
        'initial' => 'string',
        'intermediate' => 'string',
        'end' => 'string',
        'standard_ph' => 'string',
        'chlorine_residual_target' => 'string',
        'initial_pattern' => 'string',
        'name_person_responsible' => 'string',
        'name_delivery_conformity' => 'string',
        'reference_thermohygrometer' => 'string',
        'reference_multiparameter' => 'string',
        'reference_photometer' => 'string',
        'reference_turbidimeter' => 'string',
        'edition' => 'string',
        'type_customer' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_name' => 'nullable|string|max:255',
        'vehicle_arrival_time' => 'nullable|string|max:255',
        'leca_outlet' => 'nullable|string|max:255',
        'time_sample_completion' => 'nullable|string|max:255',
        'service_agreement' => 'nullable|string|max:255',
        'sample_request' => 'nullable|string|max:255',
        'time' => 'nullable|string|max:255',
        'name' => 'nullable|string|max:255',
        // 'environmental_conditions' => 'nullable|string|max:255',
        // 'potentiometer_multiparameter' => 'nullable|string|max:255',
        // 'chlorine_residual' => 'nullable|string|max:255',
        // 'turbidimeter' => 'nullable|string|max:255',
        'another_test' => 'nullable|string|max:255',
        'other_equipment' => 'nullable|string|max:255',
        'chlorine_test' => 'nullable|string|max:255',
        'factor' => 'nullable|string|max:255',
        'residual_color' => 'nullable|string|max:255',
        'media_and_DPR' => 'nullable|string|max:255',
        'mean_chlorine_value' => 'nullable|string|max:255',
        'DPR_chlorine_residual' => 'nullable|string|max:255',
        'date_last_ph_adjustment' => 'nullable|string|max:255',
        'pending' => 'nullable|string|max:255',
        'asymmetry' => 'nullable|string|max:255',
        'digital_thermometer' => 'nullable|string|max:45',
        'which' => 'nullable|string|max:255',
        'arrival_LECA' => 'nullable|string|max:255',
        'observations' => 'nullable|string',
        'witness' => 'nullable|string|max:255',
        'initial' => 'nullable|string|max:255',
        'intermediate' => 'nullable|string|max:255',
        'end' => 'nullable|string|max:255',
        'standard_ph' => 'nullable|string|max:255',
        'chlorine_residual_target' => 'nullable|string|max:255',
        'initial_pattern' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'edition' => 'nullable|string|max:255'
    ];

    protected $appends = ['has_sample_taking'];

    // Define el accessor
    public function getHasSampleTakingAttribute()
    {
        // Valida si el registro tiene la relación cargada o si existe
        return $this->lcSampleTakings()->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function usersA() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'name_delivery_conformity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function historyStartSamplings() {
        return $this->hasMany(\Modules\Leca\Models\HistoryStartSampling::class, 'lc_start_sampling_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcChlorineResidualStandards() {
        return $this->hasMany(\Modules\Leca\Models\ChlorineResidualStandard::class, 'lc_start_sampling_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSampleTakings() {
        return $this->hasMany(\Modules\Leca\Models\SampleTaking::class, 'lc_start_sampling_id');
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcPatronNtu() {
        return $this->hasMany(\Modules\Leca\Models\PatronNtu::class, 'lc_start_sampling_id');
    }
}
