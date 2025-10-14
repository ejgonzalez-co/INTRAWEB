<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Auth;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Request
 * @package Modules\SuppliesAdequacies\Models
 * @version November 12, 2024, 8:07 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $requestsSuppliesAdjustementsNeeds
 * @property integer $user_creator_id
 * @property string $subject
 * @property string $need_type
 * @property string $justification
 * @property string $url_documents
 * @property string $status
 */
class RequestSuppliesAdequacies extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_creator_id',
        'assigned_officer_id',
        'consecutive',
        'subject',
        'need_type',
        'justification',
        'url_documents',
        'status',
        'requirement_type',
        'term_type',
        'quantity_term',
        'cost_center',
        'supplier_verification',
        'supplier_name',
        'tracking',
        'expiration_date',
        'date_attention',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_creator_id' => 'integer',
        'subject' => 'string',
        'need_type' => 'string',
        'justification' => 'string',
        'url_documents' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_creator_id' => 'required',
        'subject' => 'nullable|string|max:200',
        'need_type' => 'nullable|string|max:14',
        'justification' => 'nullable|string',
        'url_documents' => 'nullable|string',
        'status' => 'nullable|string|max:20',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function userCreator() {
        return $this->belongsTo(\App\User::class, 'user_creator_id')->with(["positions","dependencies"]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function assignedOfficer() {
        return $this->belongsTo(\App\User::class,'assigned_officer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function requestsSuppliesAdjustementsNeeds() {
        return $this->hasMany(\Modules\SuppliesAdequacies\Models\RequestNeed::class, 'requests_supplies_adjustements_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function histories() {
        return $this->hasMany(\Modules\SuppliesAdequacies\Models\RequestHistory::class, 'requests_supplies_adjustements_id')->latest()->with("userCreator");
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function annotations() {
        return $this->hasMany(\Modules\SuppliesAdequacies\Models\RequestAnnotation::class, 'requests_supplies_adjustements_id')->latest()->with("users");
    }

    /**
     * Devuelve las anotaciones pendientes para el usuario actual.
     *
     * Este método define una relación para recuperar las anotaciones pendientes
     * asociadas a esta instancia de modelo para el usuario actual.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingAnnotations()
    {
        // Obtiene el ID del usuario actual autenticado
        $userId = Auth::id();

        // Define una relación "hasMany" para recuperar las anotaciones pendientes
        // donde el campo "leido_por" no contiene el ID del usuario actual
        return $this->hasMany(RequestAnnotation::class, 'requests_supplies_adjustements_id')
                    // ->whereRaw("NOT (leido_por LIKE '%{$userId}%')");
                ->where('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                ->Where('leido_por', 'not like', $userId . ',%')
                ->where('leido_por', 'not like', '%,' . $userId)
                ->Where('leido_por', 'not like', $userId );
    }
}
