<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308200943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create organization_organizations table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('organization_organizations');
        $table->addColumn('id', 'guid');
        $table->addColumn('registration_number', 'string', ['length' => 9]);
        $table->addColumn('name', 'string', ['length' => 45]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['registration_number']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('organization_organizations');
    }
}
