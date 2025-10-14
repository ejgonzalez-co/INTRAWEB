<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class TicProvider
 * @package Modules\HelpTable\Models
 * @version June 8, 2021, 9:34 am -05
 *
 * @property \Modules\HelpTable\Models\User $users
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssets
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicMaintenances
 * @property integer $users_id
 * @property integer $type_person
 * @property integer $document_type
 * @property string $identification
 * @property string $profession
 * @property string $professional_card_number
 * @property string $regime
 * @property string $address
 * @property string $phone
 * @property string $cellular
 * @property integer $state
 * @property string $ciiu_activities
 */
class TicProvider extends Model
{
        use SoftDeletes;

    public $table = 'ht_tic_provider';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'type_person',
        'document_type',
        'identification',
        'profession',
        'professional_card_number',
        'regime',
        'address',
        'phone',
        'cellular',
        'state',
        'ciiu_activities',
        'contract_start',
        'contract_end',
        'email'
    ];

    protected $appends = [
        'type_person_name',
        'document_type_name',
        'regime_name',
        'state_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'type_person' => 'integer',
        'document_type' => 'integer',
        'identification' => 'string',
        'profession' => 'string',
        'professional_card_number' => 'string',
        'regime' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'cellular' => 'string',
        'state' => 'integer',
        'ciiu_activities' => 'string',
        'contract_start' => 'date',
        'contract_end' => 'date',
        'email' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type_person' => 'required|integer',
        'document_type' => 'nullable|integer',
        'identification' => 'required|string|max:15',
        'profession' => 'nullable|string|max:191',
        'professional_card_number' => 'nullable|string|max:191',
        'regime' => 'nullable|string|max:191',
        'address' => 'nullable|string|max:191',
        'phone' => 'nullable|string|max:20',
        'cellular' => 'nullable|string|max:20',
        'state' => 'nullable|integer',
        'ciiu_activities' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'contract_start' => 'nullable',
        'contract_end' => 'nullable',
        'email'=>'nullable'

    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssets() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAsset::class, 'ht_tic_provider_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticAssetsHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicAssetsHistory::class, 'ht_tic_provider_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticMaintenances() {
        return $this->hasMany(\Modules\HelpTable\Models\TicMaintenance::class, 'ht_tic_provider_id');
    }

    /**
     * Obtiene el nombre del tipo de persona del proveedor
     * @return 
     */
    public function getTypePersonNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.type_person'), 'id', $this->type_person)->name;
    }

    /**
     * Obtiene el nombre del tipo de documento de identificacion del proveedor
     * @return 
     */
    public function getDocumentTypeNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.identification_type'), 'id', $this->document_type)->name;
    }

    /**
     * Obtiene el nombre del regimen del proveedor
     * @return 
     */
    public function getRegimeNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.regime_type'), 'id', $this->regime)->name;
    }

    /**
     * Obtiene el nombre del estado del proveedor
     * @return 
     */
    public function getStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.provider_state'), 'id', $this->state)->name;
    }
}
