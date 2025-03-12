<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250312044241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create hr_resources table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('hr_resources');
        $table->addColumn('id', 'guid');
        $table->addColumn('organization_id', 'string');
        $table->addColumn('first_name', 'string', ['length' => 45]);
        $table->addColumn('last_name', 'string', ['length' => 45]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['organization_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('hr_resources');
    }
}
