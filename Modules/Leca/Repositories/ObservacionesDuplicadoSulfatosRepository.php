<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoSulfatos;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoSulfatosRepository
 * @package Modules\leca\Repositories
 * @version December 18, 2022, 5:00 pm -05
*/

class ObservacionesDuplicadoSulfatosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_sulfatos_id',
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
        return ObservacionesDuplicadoSulfatos::class;
    }
}
