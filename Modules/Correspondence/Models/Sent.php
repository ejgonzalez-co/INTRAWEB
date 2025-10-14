<?php

namespace Modules\Correspondence\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Sent
 * @package Modules\Correspondence\Models
 * @version January 5, 2022, 10:13 am -05
 *
 * @property Modules\Intranet\Models\User $remitting
 * @property Modules\Intranet\Models\User $createUser
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceSentAnnotation2022s
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceSentCopyShare2022s
 * @property \Illuminate\Database\Eloquent\Collection $correspondenceSentRead2022s
 * @property string $consecutive
 * @property string $state
 * @property string $type_document
 * @property string $title
 * @property string $matter
 * @property string $attached
 * @property string $folios
 * @property string $annexes
 * @property string $channel
 * @property integer $received_associated
 * @property string $guide
 * @property string $sent_to_id
 * @property string $sent_to_name
 * @property integer $remitting_id
 * @property string $remitting_name
 * @property string $remitting_dependency
 * @property string $origin
 * @property string $id_pqr_finished
 * @property integer $create_user_id
 */
class Sent extends Model
{
        use SoftDeletes;

    public $table = 'correspondence_sent';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'consecutive',
        'state',
        'type_document',
        'title',
        'matter',
        'attached',
        'folios',
        'annexes',
        'channel',
        'received_associated',
        'guide',
        'sent_to_id',
        'sent_to_name',
        'remitting_id',
        'remitting_name',
        'remitting_dependency',
        'origin',
        'id_pqr_finished',
        'create_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consecutive' => 'string',
        'state' => 'string',
        'type_document' => 'string',
        'title' => 'string',
        'matter' => 'string',
        'attached' => 'string',
        'folios' => 'string',
        'annexes' => 'string',
        'channel' => 'string',
        'received_associated' => 'integer',
        'guide' => 'string',
        'sent_to_id' => 'string',
        'sent_to_name' => 'string',
        'remitting_id' => 'integer',
        'remitting_name' => 'string',
        'remitting_dependency' => 'string',
        'origin' => 'string',
        'id_pqr_finished' => 'string',
        'create_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consecutive' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:45',
        'type_document' => 'nullable|string|max:255',
        'title' => 'nullable|string',
        'matter' => 'nullable|string',
        'attached' => 'nullable|string',
        'folios' => 'nullable|string|max:255',
        'annexes' => 'nullable|string|max:255',
        'channel' => 'nullable|string|max:45',
        'received_associated' => 'nullable|integer',
        'guide' => 'nullable|string|max:45',
        'sent_to_id' => 'nullable|string|max:45',
        'sent_to_name' => 'nullable|string|max:45',
        'remitting_id' => 'required',
        'remitting_name' => 'nullable|string|max:255',
        'remitting_dependency' => 'nullable|string|max:255',
        'origin' => 'nullable|string|max:45',
        'id_pqr_finished' => 'nullable|string|max:50',
        'create_user_id' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function remitting() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'remitting_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createUser() {
        return $this->belongsTo(\Modules\Intranet\Models\User::class, 'create_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function correspondenceSentAnnotation2022s() {
        return $this->hasMany(\Modules\Correspondence\Models\CorrespondenceSentAnnotation2022::class, 'correspondence_sent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function correspondenceSentCopyShare2022s() {
        return $this->hasMany(\Modules\Correspondence\Models\CorrespondenceSentCopyShare2022::class, 'correspondence_sent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function correspondenceSentRead2022s() {
        return $this->hasMany(\Modules\Correspondence\Models\CorrespondenceSentRead2022::class, 'correspondence_sent_id');
    }
}
