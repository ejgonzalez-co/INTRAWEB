<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

/**
 * Class TechnicalConceptsHistory
 * @package Modules\HelpTable\Models
 * @version April 14, 2023, 9:12 am -05
 *
 * @property \Modules\HelpTable\Models\HtTechnicalConcept $htTechnicalConcepts
 * @property integer $ht_technical_concepts_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $status
 */
class TechnicalConceptsHistory extends Model
{
        use SoftDeletes;

    public $table = 'ht_technical_concepts_history';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'ht_technical_concepts_id',
        'user_id',
        'user_name',
        'status',
        'observations'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_technical_concepts_id' => 'integer',
        'user_id' => 'integer',
        'user_name' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_technical_concepts_id' => 'required',
        'user_id' => 'nullable',
        'user_name' => 'nullable|string|max:255',
        'status' => 'nullable|string',
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
    public function htTechnicalConcepts() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTechnicalConcept::class, 'ht_technical_concepts_id');
    }
}
