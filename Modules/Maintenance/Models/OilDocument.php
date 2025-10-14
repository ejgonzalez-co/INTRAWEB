<?php

namespace Modules\Maintenance\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OilDocument
 * @package Modules\Maintenance\Models
 * @version December 20, 2021, 1:41 am -05
 *
 * @property Modules\Maintenance\Models\MantOil $mantOils
 * @property integer $mant_oils_id
 * @property string $name
 * @property string $description
 * @property string $url_attachment
 */
class OilDocument extends Model {
    use SoftDeletes;

    public $table = 'mant_oil_documents';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'mant_oils_id',
        'name',
        'description',
        'url_attachment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mant_oils_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'url_attachment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function Oils() {
        return $this->belongsTo(\Modules\Maintenance\Models\Oil::class, 'mant_oils_id');
    }
}
