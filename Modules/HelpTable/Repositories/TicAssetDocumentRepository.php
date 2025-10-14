<?php

namespace Modules\HelpTable\Repositories;

use Modules\HelpTable\Models\TicAssetDocument;
use App\Repositories\BaseRepository;

class TicAssetDocumentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ht_tic_assets_id',
        'users_id',
        'name',
        'content',
        'url_attachments'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TicAssetDocument::class;
    }
}
