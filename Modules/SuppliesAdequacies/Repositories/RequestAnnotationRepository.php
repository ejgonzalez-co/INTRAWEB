<?php

namespace Modules\SuppliesAdequacies\Repositories;

use Modules\SuppliesAdequacies\Models\RequestAnnotation;
use App\Repositories\BaseRepository;

class RequestAnnotationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'requests_supplies_adjustements_id',
        'users_id',
        'content',
        'leido_por',
        'attached'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return RequestAnnotation::class;
    }
}
