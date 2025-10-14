<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoCloro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoCloroRepository
 * @package Modules\Leca\Repositories
 * @version August 2, 2022, 5:42 pm -05
*/

class ObservacionesDuplicadoCloroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_cloro_id'
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
        return ObservacionesDuplicadoCloro::class;
    }
}
