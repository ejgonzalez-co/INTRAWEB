<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoDureza;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoDurezaRepository
 * @package Modules\Leca\Repositories
 * @version August 3, 2022, 9:39 am -05
*/

class ObservacionesDuplicadoDurezaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'users_id',
        'name_user',
        'observation',
        'lc_ensayo_dureza_id'
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
        return ObservacionesDuplicadoDureza::class;
    }
}
