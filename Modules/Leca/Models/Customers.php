<?php

namespace Modules\Leca\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\AppBaseController;

/**
 * Class Customers
 * @package Modules\Leca\Models
 * @version November 8, 2021, 8:48 am -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $lcSamplePoints
 * @property integer $users_id
 * @property string $pin
 * @property string $password
 * @property string $identification_number
 * @property string $name
 * @property string $email
 * @property string $telephonecustoP
 * @property string $extension
 * @property string $cell_number
 * @property string $description
 * @property string $state
 */
class Customers extends Model {
    use SoftDeletes;

    public $table = 'lc_customers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $dates = ['deleted_at'];

    
    
    public $fillable = [
        'users_id',
        'contract_number',
        'pin',
        'password',
        'identification_number',
        'name',
        'email',
        'telephone',
        'extension',
        'cell_number',
        'direction',
        'description',
        'state',
        'query_report'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
        'pin' => 'string',
        'password' => 'string',
        'identification_number' => 'string',
        'name' => 'string',
        'email' => 'string',
        'contract_number'=> 'string',
        // 'telephone' => 'string',
        'extension' => 'string',
        'cell_number' => 'string',
        // 'direction' => 'String',
        'description' => 'string',
        'state' => 'string',
        'query_report' => 'string'
    ];

    protected $appends = [
        'publication_status'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pin' => 'nullable|string',
        'password' => 'nullable|string|max:255',
        'identification_number' => 'nullable|string|max:255',
        'contract_number'=> 'nullable|string|max:255',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:255',
        'extension' => 'nullable|string|max:80',
        'cell_number' => 'nullable|string|max:90',
        'direction' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'state' => 'nullable|string|max:20',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function pointLocation() {
        return $this->belongsToMany(\Modules\Leca\Models\SamplePoints::class, 'lc_customers_has_lc_sample_points','lc_customers_id','lc_sample_points_id');
    }


    /**
     * Obtiene el nombre del estado
     */
    public function getPublicationStatusAttribute() {
        return AppBaseController::getObjectOfList(config('leca.status_list'), 'id', $this->state)->name;
    }
}
