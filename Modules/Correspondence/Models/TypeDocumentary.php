<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class TypeDocumentary
 * @package Modules\Correspondence\Models
 * @version January 20, 2022, 12:30 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $externalReceived2022s
 * @property string $name
 * @property integer $state
 */
class TypeDocumentary extends Model
{
        use SoftDeletes;

    public $table = 'external_types_documentaries';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];
    
    public $fillable = [
        'name',
        'state',
        'prefix'
    ];

    protected $appends = [
        'state_name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'state' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:120',
        'state' => 'nullable|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function externalReceiveds() {
        return $this->hasMany(\Modules\Correspondence\Models\ExternalReceived::class, 'type_documentary_id');
    }

    /**
     * Obtiene el nombre del estado del tipo documental
     * @return 
     */
    public function getStateNameAttribute() {
        // Valida si el estado es diferente de vacio
        if (!empty($this->state)) {
            return AppBaseController::getObjectOfList(config('correspondence.states_documentary_types'), 'id', $this->state)->name;
        }
        return null;
    }
}
