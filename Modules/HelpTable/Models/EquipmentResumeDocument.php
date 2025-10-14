<?php

namespace Modules\HelpTable\Models;

use DateTimeInterface;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EquipmentResumeDocument
 * @package Modules\HelpTable\Models
 * @version January 24, 2023, 4:19 pm -05
 *
 * @property \Modules\HelpTable\Models\HtTicEquipmentResume $htTicEquipmentResume
 * @property integer $ht_tic_equipment_resume_id
 * @property string $name
 * @property string $description
 * @property string $url
 */
class EquipmentResumeDocument extends Model {
    use SoftDeletes;

    public $table = 'ht_tic_equipment_resume_documents';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'ht_tic_equipment_resume_id',
        'name',
        'description',
        'url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ht_tic_equipment_resume_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ht_tic_equipment_resume_id' => 'required',
        'name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:500',
        'url' => 'nullable|string|max:1000',
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
    public function htTicEquipmentResume() {
        return $this->belongsTo(\Modules\HelpTable\Models\HtTicEquipmentResume::class, 'ht_tic_equipment_resume_id');
    }
}
