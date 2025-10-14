<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class TicTypeRequest
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 8:27 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htTicKnowledgeBases
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequestHistories
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequests
 * @property string $name
 * @property integer $unit_time
 * @property integer $type_term
 * @property integer $term
 * @property integer $early
 * @property string $description
 */
class TicTypeRequest extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_type_request';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'name',
        'unit_time',
        'type_term',
        'term',
        'early',
        'description'
    ];

    protected $appends = [
        'unit_time_name',
        'type_term_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'unit_time' => 'integer',
        'type_term' => 'integer',
        'term' => 'integer',
        'early' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'name' => 'required|string|max:191',
        'unit_time' => 'required|integer',
        'type_term' => 'nullable|integer',
        'term' => 'nullable|integer',
        'early' => 'nullable|integer',
        'description' => 'nullable|string'
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
    public function ticKnowledgeBases() {
        return $this->hasMany(\Modules\HelpTable\Models\TicKnowledgeBase::class, 'ht_tic_type_request_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequestHistories() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequestHistory::class, 'ht_tic_type_request_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticRequests() {
        return $this->hasMany(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_type_request_id');
    }

    /**
     * Obtiene el nombre de la Unidad de tiempo
     * @return 
     */
    public function getUnitTimeNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.unit_time'), 'id', $this->unit_time)->name;
    }

    /**
     * Obtiene el nombre del tipo de plazo
     * @return 
     */
    public function getTypeTermNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.type_term'), 'id', $this->type_term)->name;
    }
}
