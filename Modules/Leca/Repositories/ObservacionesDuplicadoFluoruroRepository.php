<?php

namespace Modules\leca\Repositories;

use Modules\leca\Models\ObservacionesDuplicadoFluoruro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoFluoruroRepository
 * @package Modules\leca\Repositories
 * @version December 17, 2022, 11:26 am -05
*/

class ObservacionesDuplicadoFluoruroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayo_fluoruros_id',
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
        return ObservacionesDuplicadoFluoruro::class;
    }
}
