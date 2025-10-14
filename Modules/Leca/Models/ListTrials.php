<?php

namespace Modules\Leca\Models;

use Modules\Leca\Models\Color;
use Modules\Leca\Models\Conductividad;
use Modules\Leca\Models\Olor;
use Modules\Leca\Models\SustanciasFlotantes;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ListTrials
 * @package Modules\Leca\Models
 * @version February 11, 2022, 10:02 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $lcEspectofotometricos
 * @property \Illuminate\Database\Eloquent\Collection $lcSampleTakings
 * @property string $type
 * @property string $number_list
 * @property string $code
 * @property string $name
 * @property string $description
 */
class ListTrials extends Model
{
    use SoftDeletes;

    public $table = 'lc_list_trials';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'type',
        'number_list',
        'code',
        'name',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'number_list' => 'string',
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'nullable|string|max:45',
        'number_list' => 'nullable|string|max:45',
        'code' => 'nullable|string|max:150',
        'name' => 'nullable|string|max:150',
        'description' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcNitritos()
    {
        return $this->hasMany(\Modules\Leca\Models\Nitritos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcNitratos()
    {
        return $this->hasMany(\Modules\Leca\Models\Nitratos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcHierro()
    {
        return $this->hasMany(\Modules\Leca\Models\Hierro::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcFosfatos()
    {
        return $this->hasMany(\Modules\Leca\Models\Fosfatos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcAluminio()
    {
        return $this->hasMany(\Modules\Leca\Models\Aluminio::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcPlomo()
    {
        return $this->hasMany(\Modules\Leca\Models\Plomo::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCadmio()
    {
        return $this->hasMany(\Modules\Leca\Models\Cadmio::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcMercurio()
    {
        return $this->hasMany(\Modules\Leca\Models\Mercurio::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcHidrocarburos()
    {
        return $this->hasMany(\Modules\Leca\Models\Hidrocarburos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcPlaguicidas()
    {
        return $this->hasMany(\Modules\Leca\Models\Plaguicida::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcTrialometanos()
    {
        return $this->hasMany(\Modules\Leca\Models\Trialometanos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCloruro()
    {
        return $this->hasMany(\Modules\Leca\Models\Cloruro::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCloroResidual()
    {
        return $this->hasMany(\Modules\Leca\Models\CloroResidual::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCalcio()
    {
        return $this->hasMany(\Modules\Leca\Models\Calcio::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcDurezaTotal()
    {
        return $this->hasMany(\Modules\Leca\Models\DurezaTotal::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcAcidez()
    {
        return $this->hasMany(\Modules\Leca\Models\Acidez::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcFluoruros()
    {
        return $this->hasMany(\Modules\Leca\Models\Fluoruros::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSulfatos()
    {
        return $this->hasMany(\Modules\Leca\Models\Sulfatos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcAlcalinidad()
    {
        return $this->hasMany(\Modules\Leca\Models\Alcalinidad::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcPh()
    {
        return $this->hasMany(\Modules\Leca\Models\Ph::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcTurbidez()
    {
        return $this->hasMany(\Modules\Leca\Models\Turbidez::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcColiformesTotales()
    {
        return $this->hasMany(\Modules\Leca\Models\ColiformesTotales::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcEscherichiaColi()
    {
        return $this->hasMany(\Modules\Leca\Models\EscherichiaColi::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcBacteriasHeterotroficas()
    {
        return $this->hasMany(\Modules\Leca\Models\BacteriasHeterotroficas::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcColor()
    {
        return $this->hasMany(Color::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcOlor()
    {
        return $this->hasMany(Olor::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcConductividad()
    {
        return $this->hasMany(Conductividad::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSustanciasFlotantes()
    {
        return $this->hasMany(SustanciasFlotantes::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCarbonoOrganico()
    {
        return $this->hasMany(\Modules\Leca\Models\CarbonoOrganico::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSolidos()
    {
        return $this->hasMany(\Modules\Leca\Models\Solidos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSolidosSecos()
    {
        return $this->hasMany(\Modules\Leca\Models\SolidosSecos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcBlancos()
    {
        return $this->hasMany(\Modules\Leca\Models\Blancos::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcPatron()
    {
        return $this->hasMany(\Modules\Leca\Models\Patron::class, 'lc_list_trials_id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcCriticalEquipments()
    {
        return $this->hasMany(\Modules\Leca\Models\CriticalEquipment::class, 'lc_list_trials_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcSampleTakings()
    {
        return $this->belongsToMany(\Modules\Leca\Models\LcSampleTaking::class, 'lc_sample_taking_has_lc_list_trials');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcMonthlyRoutinesLcOfficials()
    {
        return $this->belongsToMany(\Modules\Leca\Models\MonthlyRoutinesOfficials::class, 'lc_list_trials_has_lc_monthly_routines_officials');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lcSampleTakingLcTrials()
    {
        return $this->belongsToMany(\Modules\Leca\Models\SampleTaking::class, 'lc_sample_taking_has_lc_list_trials');
    }
}