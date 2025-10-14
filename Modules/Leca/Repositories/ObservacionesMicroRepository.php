<?php

namespace Modules\Leca\Repositories;

use Modules\Leca\Models\ObservacionesMicro;
use App\Repositories\BaseRepository;

/**
 * Class ObservacionesMicroRepository
 * @package Modules\Leca\Repositories
 * @version November 11, 2022, 5:42 pm -05
*/

class ObservacionesMicroRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lc_ensayos_microbiologicos_id',
        'users_id',
        'ensayo',
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
        return ObservacionesMicro::class;
    }
}
