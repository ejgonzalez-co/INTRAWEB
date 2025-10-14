<?php

namespace Modules\Intranet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class Citizen
 * @package Modules\Intranet\Models
 * @version January 20, 2022, 1:56 am -05
 *
 * @property Modules\Intranet\Models\User $user
 * @property integer $user_id
 * @property integer $type_person
 * @property integer $type_document
 * @property string $first_name
 * @property string $second_name
 * @property string $first_surname
 * @property string $second_surname
 * @property string $address
 * @property string $phone
 */
class Citizen extends Model
{
    use SoftDeletes;

    public $table = 'citizens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'states_id',
        'city_id',
        'type_person',
        'type_document',
        'document_number',
        'name',
        'first_name',
        'second_name',
        'first_surname',
        'second_surname',
        'address',
        'phone',
        'solicitudes_via_email'
    ];

    protected $appends = [
        'type_person_name',
        'type_document_name',
        'email'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'states_id' => 'integer',
        'city_id' => 'integer',
        'type_person' => 'integer',
        'type_document' => 'integer',
        'document_number' => 'integer',
        'name' => 'string',
        'first_name' => 'string',
        'second_name' => 'string',
        'first_surname' => 'string',
        'second_surname' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'solicitudes_via_email' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type_person' => 'nullable|integer',
        'type_document' => 'nullable|integer',
        'document_number' => 'nullable',
        'name' => 'nullable|string|max:120',
        'first_name' => 'nullable|string|max:120',
        'second_name' => 'nullable|string|max:120',
        'first_surname' => 'nullable|string|max:120',
        'second_surname' => 'nullable|string|max:120',
        'address' => 'nullable|string|max:120',
        'phone' => 'nullable|string|max:20',
        'solicitudes_via_email' => 'nullable|string|max:10',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * Obtiene el nombre del estado del tipo documental
     * @return 
     */
    public function getEmailAttribute() {
        return $this->users ? $this->users->email : 'N/A';
    }

    /**
     * Obtiene el nombre del estado del tipo documental
     * @return 
     */
    public function getTypePersonNameAttribute() {
        $typePerson = AppBaseController::getObjectOfList(config('correspondence.type_person'), 'id', $this->type_person);
        return $typePerson ? $typePerson->name : 'No definido';
    }
    
    /**
     * Obtiene el nombre del estado del tipo documental
     * @return string
     */
    public function getTypeDocumentNameAttribute() {
        $typeDocument = AppBaseController::getObjectOfList(config('correspondence.type_document'), 'id', $this->type_document);
        return $typeDocument ? $typeDocument->name : 'No definido';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function states() {
        return $this->belongsTo(\App\Models\State::class, 'states_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cities() {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }
}
