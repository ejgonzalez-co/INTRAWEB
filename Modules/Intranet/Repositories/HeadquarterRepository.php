<?php

namespace Modules\Intranet\Repositories;

use Modules\Intranet\Models\Headquarter;
use App\Repositories\BaseRepository;

/**
 * Class HeadquarterRepository
 * @package Modules\Intranet\Repositories
 * @version June 17, 2020, 4:36 pm UTC
*/

class HeadquarterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'descripcion',
        'codigo'
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
        return Headquarter::class;
    }
}
