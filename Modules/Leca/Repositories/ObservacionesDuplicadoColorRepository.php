<?php

namespace Modules\leca\Repositories;

use Modules\Leca\Models\ObservacionesDuplicadoColor;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesDuplicadoColorRepository
 * @package Modules\leca\Repositories
 * @version January 25, 2023, 4:42 pm -05
*/

class ObservacionesDuplicadoColorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name_user',
        'observation',
        'users_id',
        'lc_ensayo_color_id'
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
        return ObservacionesDuplicadoColor::class;
    }
}
