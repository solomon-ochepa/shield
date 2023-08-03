<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Models;

use CodeIgniter\Shield\Entities\Group;

class GroupModel extends BaseModel
{
    protected $returnType    = Group::class;
    protected $allowedFields = [
        'title',
        'slug',
        'description',
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];

    protected function initialize(): void
    {
        parent::initialize();

        $this->table = $this->tables['groups'];
    }
}
