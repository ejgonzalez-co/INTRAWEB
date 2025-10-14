<?php

namespace Modules\SuppliesAdequacies\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class KnowledgeBase
 * @package Modules\SuppliesAdequacies\Models
 * @version November 14, 2024, 2:41 pm -05
 *
 * @property \Modules\SuppliesAdequacies\Models\RequestsSuppliesAdjustement $requestsSuppliesAdjustements
 * @property \Modules\SuppliesAdequacies\Models\User $userCreator
 * @property integer $user_creator
 * @property integer $requests_supplies_adjustements_id
 * @property string $knowledge_type
 * @property string $subject_knowledge
 * @property string $description
 * @property string $status
 * @property string $url_attacheds
 */
class KnowledgeBase extends Model {
    use SoftDeletes;

    public $table = 'requests_supplies_adjustements_knowledge_bases';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'user_creator',
        'requests_supplies_adjustements_id',
        'knowledge_type',
        'subject_knowledge',
        'description',
        'status',
        'url_attacheds'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_creator' => 'integer',
        'requests_supplies_adjustements_id' => 'integer',
        'knowledge_type' => 'string',
        'subject_knowledge' => 'string',
        'description' => 'string',
        'status' => 'string',
        'url_attacheds' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_creator' => 'required',
        'requests_supplies_adjustements_id' => 'required',
        'knowledge_type' => 'nullable|string|max:45',
        'subject_knowledge' => 'nullable|string|max:200',
        'description' => 'nullable|string',
        'status' => 'nullable|string|max:45',
        'url_attacheds' => 'nullable|string',
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
    public function requestsSuppliesAdjustements() {
        return $this->belongsTo(\Modules\SuppliesAdequacies\Models\RequestsSuppliesAdjustement::class, 'requests_supplies_adjustements_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function userCreator() {
        return $this->belongsTo(\App\User::class, 'user_creator');
    }
}
