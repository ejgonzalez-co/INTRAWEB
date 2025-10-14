<?php

namespace Modules\Intranet\Models\ModelsJoomla;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Externa
 * @package Modules\Intranet\Models
 * @version Oct 14, 2020, 3:55 pm UTC
 *
 * @property \Modules\Intranet\Models\Sede $idSede
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property integer $id_sede
 * @property string $codigo
 * @property string $nombre
 * @property string $codigo_oficina_productora
 */
class Externa extends Model
{    
   /**
     * The database connection used by the model.
     *
     * @var string
     */
   protected $connection = 'joomla';

   /**
    * The database table used by the model.
    *
    * @var string
    */
  	protected $table = "externa";

	protected $primaryKey = 'cf_id'; 

   const CREATED_AT = 'cf_created';
   const UPDATED_AT = 'cf_modified';
}
