<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class SampleTaking
 * @package Modules\Leca\Models
 * @version January 4, 2022, 10:25 am -05
 *
 * @property Modules\Leca\Models\LcSamplePoint $lcSamplePoints
 * @property Modules\Leca\Models\LcStartSampling $lcStartSampling
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcDynamicPh(x1)Lists
 * @property \Illuminate\Database\Eloquent\Collection $lcDynamicPh(x2)Lists
 * @property \Illuminate\Database\Eloquent\Collection $lcDynamicPhLists
 * @property \Illuminate\Database\Eloquent\Collection $lcResidualChlorineLists
 * @property \Illuminate\Database\Eloquent\Collection $lcListTrials
 * @property integer $lc_start_sampling_id
 * @property integer $lc_sample_points_id
 * @property integer $users_id
 * @property string $user_name
 * @property string $sample_reception_code
 * @property string $address
 * @property string $type_water
 * @property string $humidity
 * @property string $temperature
 * @property string $hour_from_to
 * @property string $prevailing_climatic_characteristics
 * @property string $test_perform
 * @property string $container_number
 * @property string $hour
 * @property string $according
 * @property string $sample_characteristics
 * @property string $observations
 * @property string $refrigeration
 * @property string $filtered_sample
 * @property string $hno3
 * @property string $h2so4
 * @property string $hci
 * @property string $naoh
 * @property string $acetate
 * @property string $ascorbic_acid
 * @property string $charge
 * @property string $process
 * @property string $url_qr
 */
class SampleTaking extends Model
{
    use SoftDeletes;

    public $table = 'lc_sample_taking';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lc_start_sampling_id',
        'lc_sample_points_id',
        'lc_sampling_schedule_id',
        'users_id',
        'user_name',
        'sample_reception_code',
        'address',
        'type_water',
        'humidity',
        'temperature',
        'hour_from_to',
        'prevailing_climatic_characteristics',
        'test_perform',
        'container_number',
        'hour',
        'according',
        'sample_characteristics',
        'observations',
        'refrigeration',
        'filtered_sample',
        'ph_promedio',
        'temperatura_promedio',
        'hno3',
        'h2so4',
        'hci',
        'naoh',
        'acetate',
        'ascorbic_acid',
        'charge',
        'process',
        'url_qr',
        'chlorine_reception',
        'reception_ph',
        'ntu_reception',
        'conductivity_reception',
        'other_reception',
        'type_receipt',
        'volume_liters',
        'persevering_addiction',
        't_initial_receipt',
        't_final_receipt',
        'according_receipt',
        'observation_receipt',
        'is_accepted',
        'justification_receipt',
        'reception_date',
        'name_receipt',
        'url_receipt',
        'requested_parameters',
        'state_receipt',
        'estado_analisis',
        'duplicado',
        'cloro_promedio',
        'turbidez',
        'duplicado_cloro',
        'vigencia',
        'observations_edit',
        'reception_hour'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lc_start_sampling_id' => 'integer',
        'lc_sample_points_id' => 'integer',
        'lc_sampling_schedule_id' => 'integer',
        'users_id' => 'integer',
        'user_name' => 'string',
        'sample_reception_code' => 'string',
        'address' => 'string',
        'type_water' => 'string',
        'duplicado' => 'string',
        'humidity' => 'string',
        'temperature' => 'string',
        'hour_from_to' => 'string',
        'prevailing_climatic_characteristics' => 'string',
        'test_perform' => 'string',
        'container_number' => 'string',
        'hour' => 'string',
        'according' => 'string',
        'sample_characteristics' => 'string',
        'observations' => 'string',
        'refrigeration' => 'string',
        'filtered_sample' => 'string',
        'hno3' => 'string',
        'h2so4' => 'string',
        'hci' => 'string',
        'naoh' => 'string',
        'acetate' => 'string',
        'ascorbic_acid' => 'string',
        'charge' => 'string',
        'process' => 'string',
        'url_qr' => 'string',
        'chlorine_reception' => 'string',
        'reception_ph' => 'string',
        'ntu_reception' => 'string',
        'conductivity_reception' => 'string',
        'other_reception' => 'string',
        'type_receipt' => 'string',
        'volume_liters' => 'string',
        'persevering_addiction' => 'string',
        't_initial_receipt' => 'string',
        't_final_receipt' => 'string',
        'according_receipt' => 'string',
        'observation_receipt' => 'string',
        'is_accepted' => 'string',
        'justification_receipt' => 'string',
        'reception_date' => 'string',
        'name_receipt' => 'string',
        'url_receipt' => 'string',
        'requested_parameters' => 'string',
        'state_receipt' => 'integer',
        'created_at' => 'string',
        'estado_analisis' => 'string',
        'vigencia' => 'string',
        'observations_edit'=> 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_name' => 'nullable|string|max:255',
        'sample_reception_code' => 'nullable|string|max:45',
        'address' => 'nullable|string|max:255',
        'type_water' => 'nullable|string|max:255',
        'humidity' => 'nullable|string|max:255',
        'temperature' => 'nullable|string|max:255',
        'hour_from_to' => 'nullable|string|max:255',
        'prevailing_climatic_characteristics' => 'nullable|string|max:255',
        'test_perform' => 'nullable|string|max:255',
        'container_number' => 'nullable|string|max:45',
        'hour' => 'nullable|string|max:255',
        'according' => 'nullable|string|max:255',
        'sample_characteristics' => 'nullable|string|max:45',
        'observations' => 'nullable|string',
        'refrigeration' => 'nullable|string|max:45',
        'filtered_sample' => 'nullable|string|max:45',
        'hno3' => 'nullable|string|max:45',
        'h2so4' => 'nullable|string|max:45',
        'hci' => 'nullable|string|max:45',
        'naoh' => 'nullable|string|max:45',
        'acetate' => 'nullable|string|max:45',
        'ascorbic_acid' => 'nullable|string|max:45',
        'charge' => 'nullable|string|max:255',
        'process' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'observations_edit' => 'nullable|string|min:30',
        'reception_date' => 'nullable|string',
        'chlorine_reception' => 'nullable|string',
        'reception_ph' => 'nullable|string',
        'ntu_reception' => 'nullable|string',
        'conductivity_reception' => 'nullable|string',
        'other_reception' => 'nullable|string',
        'type_receipt' => 'nullable|string',
        'volume_liters' => 'nullable|string',
        'persevering_addiction' => 'nullable|string',
        't_initial_receipt' => 'nullable|string',
        't_final_receipt' => 'nullable|string',
        'according_receipt' => 'nullable|string',
        'observation_receipt' => 'nullable|string',
        'is_accepted' => 'nullable|string',
        'justification_receipt' => 'nullable|string',
        'name_receipt' => 'nullable|string',
        'url_receipt' => 'nullable|string',
        'requested_parameters' => 'nullable|string',
        'state_receipt' => 'nullable|integer',

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

    protected $appends = [
        'type_customer_name'
    ];

    public function getTypeCustomerNameAttribute(){
        
        return $this->lcStartSampling->type_customer;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcSamplePoints()
    {
        return $this->belongsTo(\Modules\Leca\Models\SamplePoints::class, 'lc_sample_points_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lcStartSampling()
    {
        return $this->belongsTo(\Modules\Leca\Models\StartSampling::class, 'lc_start_sampling_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users()
    {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDynamicPhOneLists()
    {
        return $this->hasMany(\Modules\Leca\Models\DynamicPhOneList::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDynamicPhTwoLists()
    {
        return $this->hasMany(\Modules\Leca\Models\DynamicPhTwoList::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDynamicPhLists()
    {
        return $this->hasMany(\Modules\Leca\Models\DynamicPhList::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcResidualChlorineLists()
    {
        return $this->hasMany(\Modules\Leca\Models\ResidualChlorineList::class, 'lc_sample_taking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcListTrials()
    {
        return $this->belongsToMany(\Modules\Leca\Models\ListTrials::class, 'lc_sample_taking_has_lc_list_trials', 'lc_sample_taking_id', 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcListTrialsTwo()
    {
        return $this->belongsToMany(\Modules\Leca\Models\ListTrials::class, 'lc_sample_taking_has_lc_list_trials', 'lc_sample_taking_id', 'lc_list_trials_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcHistorySampleTakings()
    {
        return $this->hasMany(\Modules\Leca\Models\HistorySampleTaking::class, 'lc_sample_taking_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function programacion()
    {
        return $this->belongsTo(\Modules\Leca\Models\SamplingSchedule::class, 'lc_sampling_schedule_id');
    }
}
