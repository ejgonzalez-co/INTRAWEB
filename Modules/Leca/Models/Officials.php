<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class Officials
 * @package Modules\Leca\Models
 * @version November 17, 2021, 10:26 am -05
 *
 * @property Modules\Leca\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $lcMonthlyRoutines
 * @property \Illuminate\Database\Eloquent\Collection $lcWeeklyRoutines
 * @property integer $users_id
 * @property string $pin
 * @property string $password
 * @property string $identification_number
 * @property string $name
 * @property string $email
 * @property string $telephone
 * @property string $direction
 * @property string $charge
 * @property string $functions
 * @property string $state
 * @property string $receptionist
 * @property string $firm
 */
class Officials extends Model {
    use SoftDeletes;

    public $table = 'lc_officials';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'pin',
        'password',
        'identification_number',
        'name',
        'email',
        'telephone',
        'direction',
        'charge',
        'functions',
        'state',
        'receptionist',
        'firm'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'pin' => 'string',
        'password' => 'string',
        'identification_number' => 'string',
        'name' => 'string',
        'email' => 'string',
        'telephone' => 'string',
        'direction' => 'string',
        'charge' => 'string',
        'functions' => 'string',
        'state' => 'string',
        'receptionist' => 'string',
        // 'firm' => 'string'
    ];

    protected $appends = [
        'publication_status'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pin' => 'nullable|string|max:255',
        'password' => 'nullable|string|max:255',
        'identification_number' => 'nullable|string|max:255',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:255',
        'direction' => 'nullable|string|max:255',
        'charge' => 'nullable|string|max:255',
        'functions' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:20',
        'receptionist' => 'nullable|string|max:45',
        // 'firm' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\Modules\Leca\Models\User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcMonthlyRoutines() {
        return $this->belongsToMany(\Modules\Leca\Models\MonthlyRoutines::class, 'lc_monthly_routines_has_lc_officials');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lcWeeklyRoutines() {
        return $this->belongsToMany(\Modules\Leca\Models\WeeklyRoutine::class, 'lc_weekly_routines_has_lc_officials');
    }

    /**
     * Obtiene el nombre del estado
     */
    public function getPublicationStatusAttribute() {
        return AppBaseController::getObjectOfList(config('leca.status_list'), 'id', $this->state)->name;
    }
}
