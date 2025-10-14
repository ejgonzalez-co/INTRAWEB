<?php

namespace Modules\Maintenance\Repositories;

use Modules\Maintenance\Models\DocumentOrder;
use App\Repositories\BaseRepository;

/**
 * Class DocumentOrderRepository
 * @package Modules\Maintenance\Repositories
 * @version January 17, 2024, 4:53 pm -05
*/

class DocumentOrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mant_sn_orders_id',
        'users_id',
        'nombre',
        'estado',
        'adjunto'
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
        return DocumentOrder::class;
    }
}
