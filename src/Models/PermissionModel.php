<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Models;

use CodeIgniter\Shield\Entities\Permission;

class PermissionModel extends BaseModel
{
    protected $returnType    = Permission::class;
    protected $allowedFields = [
        'title',
        'description'
    ];
    protected $useTimestamps = true;
    protected $validationRules    = [];
    protected $validationMessages = [];

    protected function initialize(): void
    {
        parent::initialize();

        $this->table = $this->tables['permissions'];
    }
}
