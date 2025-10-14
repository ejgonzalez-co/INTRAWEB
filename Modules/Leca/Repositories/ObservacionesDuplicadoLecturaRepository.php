<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoLectura;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoLecturaRepository
 * @package Modules\Leca\Repositories
 * @version August 3, 2022, 5:46 pm -05
*/

class ObservacionesDuplicadoLecturaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_lectura_id'
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
        return ObservacionesDuplicadoLectura::class;
    }
}
