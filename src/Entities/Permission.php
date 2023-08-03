<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\Shield\Models\PermissionModel;

/**
 * Represents a single Group.
 */
class Permission extends Entity
{
    /**
     * Auth Table names
     */
    private array $tables;

    public function __construct(?array $data = null)
    {
        /** @var Auth $authConfig */
        $authConfig = config('Auth');

        parent::__construct($data);

        $this->tables = $authConfig->tables;
    }

    public function getUsers()
    {
        $users = model(PermissionModel::class)->query(
            "SELECT {$this->tables['users']}.*
            FROM {$this->tables['users']}
            JOIN {$this->tables['permissions_users']} ON {$this->tables['users']}.id = {$this->tables['permissions_users']}.user_id
            where {$this->tables['permissions_users']}.permission = '{$this->title}'"
        )->getResultObject();

        return $users;
    }
}
