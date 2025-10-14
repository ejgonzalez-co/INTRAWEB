<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;
use DateTimeInterface;

/**
 * Class TicKnowledgeBase
 * @package Modules\HelpTable\Models
 * @version June 5, 2021, 10:16 am -05
 *
 * @property \Modules\HelpTable\Models\HtTicRequest $htTicRequests
 * @property \Modules\HelpTable\Models\HtTicTypeRequest $htTicTypeRequest
 * @property \Modules\HelpTable\Models\User $users
 * @property integer $ht_tic_type_request_id
 * @property integer $users_id
 * @property integer $ht_tic_requests_id
 * @property string $affair
 * @property string $knowledge_description
 * @property string $attached
 * @property integer $knowledge_state
 */
class TicKnowledgeBase extends Model
{
    use SoftDeletes;

    public $table = 'ht_tic_knowledge_base';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_tic_type_request_id',
        'users_id',
        'ht_tic_requests_id',
        'affair',
        'knowledge_description',
        'attached',
        'knowledge_state',
        'enlace'
    ];

    protected $appends = [
        'knowledge_state_name','enlace_youtube'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_type_request_id' => 'integer',
        'users_id' => 'integer',
        'ht_tic_requests_id' => 'integer',
        'affair' => 'string',
        'knowledge_description' => 'string',
        'attached' => 'string',
        'knowledge_state' => 'integer',
        'enlace' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_type_request_id' => 'nullable',
        'ht_tic_requests_id' => 'nullable',
        'affair' => 'nullable|string',
        'knowledge_description' => 'nullable|string',
        // 'attached'  => 'nullable|string',
        'knowledge_state' => 'nullable|integer',
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
    public function ticRequests() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicRequest::class, 'ht_tic_requests_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ticTypeRequest() {
        return $this->belongsTo(\Modules\HelpTable\Models\TicTypeRequest::class, 'ht_tic_type_request_id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function users() {
        return $this->belongsTo(\App\User::class, 'users_id');
    }

    /**
     * Obtiene el nombre del estado del conocimiento
     * @return 
     */
    public function getKnowledgeStateNameAttribute() {
        return AppBaseController::getObjectOfList(config('help_table.state_knowledge_tic'), 'id', $this->knowledge_state)->name;
    }

    /**
     * Obtiene el nombre del estado del conocimiento
     * @return 
     */
    public function getEnlaceYoutubeAttribute() {
        if (strpos($this->enlace, 'youtube') !== false && !empty($this->enlace)) {
            parse_str(parse_url($this->enlace, PHP_URL_QUERY), $queryParams);
            if (isset($queryParams['v'])) {
                return 'https://www.youtube.com/embed/' . $queryParams['v'];
            }
        }
        return false;
    }
}
