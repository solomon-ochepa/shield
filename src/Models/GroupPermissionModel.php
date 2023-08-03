<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Models;

use CodeIgniter\Shield\Entities\GroupPermission;

class GroupPermissionModel extends BaseModel
{
    protected $returnType    = GroupPermission::class;
    protected $allowedFields = [
        'group',
        'permission'
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];

    protected function initialize(): void
    {
        parent::initialize();

        $this->table = $this->tables['groups_permissions'];
    }
}
