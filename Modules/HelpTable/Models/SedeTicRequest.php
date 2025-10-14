<?php

namespace Modules\HelpTable\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SedeTicRequest
 * @package Modules\HelpTable\Models
 * @version October 15, 2021, 12:05 pm -05
 *
 * @property \Illuminate\Database\Eloquent\Collection $htDependenciasTicRequests
 * @property \Illuminate\Database\Eloquent\Collection $htTicRequests
 * @property string $name
 */
class SedeTicRequest extends Model
{
        use SoftDeletes;

        public $table = 'ht_sedes_tic_request';

        const CREATED_AT = 'created_at';
        const UPDATED_AT = 'updated_at';


        protected $dates = ['deleted_at'];



        public $fillable = [
            'name',
            'estado'
        ];

        /**
         * The attributes that should be casted to native types.
         *
         * @var array
         */
        protected $casts = [
            'id' => 'integer',
            'estado' => 'string',
            'name' => 'string'
        ];

        /**
         * Validation rules
         *
         * @var array
         */
        public static $rules = [
            'estado' => 'nullable|string|max:45',
            'name' => 'nullable|string|max:255',
            'created_at' => 'nullable',
            'updated_at' => 'nullable'
        ];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         **/
        public function htDependenciasTicRequests() {
            return $this->hasMany(\Modules\HelpTable\Models\HtDependenciasTicRequest::class, 'ht_sedes_tic_request_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         **/
        public function htTicRequests() {
            return $this->hasMany(\Modules\HelpTable\Models\HtTicRequest::class, 'ht_sedes_tic_request_id');
        }
}
