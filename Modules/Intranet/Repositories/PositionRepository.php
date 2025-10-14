<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Position;
use App\Repositories\BaseRepository;

/**
 * Class PositionRepository
 * @package Modules\Intranet\Repositories
 * @version June 17, 2020, 4:18 pm UTC
*/

class PositionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'descripcion'
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
        return Position::class;
    }
}
