<?php

declare(strict_types=1);

namespace CodeIgniter\Shield\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthGroupsTable extends Migration
{
    /**
     * Auth Table names
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        /** @var Auth $authConfig */
        $authConfig = config('Auth');

        parent::__construct($forge);

        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'         => ['type' => 'varchar', 'constraint' => 255],
            'slug'          => ['type' => 'varchar', 'constraint' => 255],
            'description'   => ['type' => 'text', 'null' => true],
            'created_at'    => ['type' => 'datetime', 'null' => true],
            'updated_at'    => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable($this->tables['groups'], true);
    }

    public function down()
    {
        $this->forge->dropTable($this->tables['groups'], true);
    }
}
