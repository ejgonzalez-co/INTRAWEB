<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Dependency
 * @package Modules\Maintenance\Models
 * @version August 27, 2021, 3:45 pm -05
 *
 * @property Modules\Maintenance\Models\Sede $idSede
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssets
 * @property \Illuminate\Database\Eloquent\Collection $htTicAssetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicMaintenances
 * @property \Illuminate\Database\Eloquent\Collection $mantHistoryMinorEquipments
 * @property \Illuminate\Database\Eloquent\Collection $mantMinorEquipmentFuels
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheets
 * @property \Illuminate\Database\Eloquent\Collection $pcInvestmentTechnicalSheetsHistories
 * @property \Illuminate\Database\Eloquent\Collection $pcPaaVersions
 * @property \Illuminate\Database\Eloquent\Collection $usersHistories
 * @property \Illuminate\Database\Eloquent\Collection $workHistories
 * @property \Illuminate\Database\Eloquent\Collection $workHistoriesPs
 * @property integer $id_sede
 * @property string $codigo
 * @property string $nombre
 * @property string $codigo_oficina_productora
 * @property integer $cf_user_id
 */
class Dependency extends Model {
    use SoftDeletes;

    public $table = 'dependencias';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'id_sede',
        'codigo',
        'nombre',
        'codigo_oficina_productora',
        'cf_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_sede' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'codigo_oficina_productora' => 'string',
        'cf_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_sede' => 'required',
        'codigo' => 'nullable|string|max:45',
        'nombre' => 'required|string|max:80',
        'codigo_oficina_productora' => 'nullable|string|max:45',
        'cf_user_id' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idSede() {
        return $this->belongsTo(\Modules\Maintenance\Models\Sede::class, 'id_sede');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function htTicAssets() {
        return $this->hasMany(\Modules\Maintenance\Models\HtTicAsset::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function htTicAssetsHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\HtTicAssetsHistory::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function htTicMaintenances() {
        return $this->hasMany(\Modules\Maintenance\Models\HtTicMaintenance::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantHistoryMinorEquipments() {
        return $this->hasMany(\Modules\Maintenance\Models\MantHistoryMinorEquipment::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mantMinorEquipmentFuels() {
        return $this->hasMany(\Modules\Maintenance\Models\MantMinorEquipmentFuel::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcInvestmentTechnicalSheets() {
        return $this->hasMany(\Modules\Maintenance\Models\PcInvestmentTechnicalSheet::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcInvestmentTechnicalSheetsHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\PcInvestmentTechnicalSheetsHistory::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pcPaaVersions() {
        return $this->hasMany(\Modules\Maintenance\Models\PcPaaVersion::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function usersHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\UsersHistory::class, 'id_dependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistories() {
        return $this->hasMany(\Modules\Maintenance\Models\WorkHistory::class, 'dependencias_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workHistoriesPs() {
        return $this->hasMany(\Modules\Maintenance\Models\WorkHistoriesP::class, 'dependencias_id');
    }
}
