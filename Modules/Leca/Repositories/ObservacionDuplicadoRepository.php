<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionDuplicado;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionDuplicadoRepository
 * @package Modules\Leca\Repositories
 * @version May 31, 2022, 8:03 am -05
*/

class ObservacionDuplicadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_aluminio_id',
        'users_id',
        'name_user',
        'observation'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ObservacionDuplicado::class;
    }
}
