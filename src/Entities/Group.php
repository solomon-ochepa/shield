<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\Shield\Models\GroupModel;

/**
 * Represents a single User Group
 * and provides utility functions.
 */
class Group extends Entity
{
    protected ?array $permissions = null;

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

    /**
     * Returns the group users.
     */
    public function getUsers(): array
    {
        $users = model(GroupModel::class)->query(
            "SELECT {$this->tables['users']}.*
            FROM {$this->tables['users']}
            JOIN {$this->tables['groups_users']} ON {$this->tables['users']}.id = {$this->tables['groups_users']}.user_id
            where {$this->tables['groups_users']}.group = '{$this->slug}'"
        )->getResultObject();

        return $users;
    }

    public function getPermissions(): array
    {
        $permissions = model(GroupModel::class)->query(
            "SELECT {$this->tables['permissions']}.*
            FROM {$this->tables['permissions']}
            JOIN {$this->tables['groups_permissions']} ON {$this->tables['permissions']}.title = {$this->tables['groups_permissions']}.permission
            where {$this->tables['groups_permissions']}.group = '{$this->slug}'"
        )->getResultObject();

        return $permissions;
    }

    /**
     * Returns the permissions for this group.
     */
    public function permissions(): array
    {
        $this->populatePermissions();

        return $this->permissions;
    }

    /**
     * Overrides and saves all permissions in the class
     * with the permissions array that is passed in.
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;

        $matrix = setting('AuthGroups.matrix');

        $matrix[$this->alias] = $permissions;

        setting('AuthGroups.matrix', $matrix);
    }

    /**
     * Adds a single permission to this group and saves it.
     */
    public function addPermission(string $permission): void
    {
        $this->populatePermissions();

        array_unshift($this->permissions, $permission);

        $this->setPermissions($this->permissions);
    }

    /**
     * Removes a single permission from this group and saves it.
     */
    public function removePermission(string $permission): void
    {
        $this->populatePermissions();

        unset($this->permissions[array_search($permission, $this->permissions, true)]);

        $this->setPermissions($this->permissions);
    }

    /**
     * Determines if the group has the given permission
     */
    public function can(string $permission): bool
    {
        $this->populatePermissions();

        // Check exact match
        if (! empty($this->permissions) && in_array($permission, $this->permissions, true)) {
            return true;
        }

        // Check wildcard match
        $check = substr($permission, 0, strpos($permission, '.')) . '.*';

        return ! empty($this->permissions) && in_array($check, $this->permissions, true);
    }

    /**
     * Loads our permissions for this group.
     */
    private function populatePermissions(): void
    {
        if ($this->permissions !== null) {
            return;
        }

        $this->permissions = setting('AuthGroups.matrix')[$this->alias] ?? [];
    }
}
