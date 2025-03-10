<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250310033336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create crm_prospects table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('crm_prospects');
        $table->addColumn('id', 'guid');
        $table->addColumn('organization_id', 'string');
        $table->addColumn('registration_number', 'string');
        $table->addColumn('name', 'string');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['organization_id']);
        $table->addIndex(['registration_number']);
        $table->addUniqueIndex(['organization_id', 'registration_number']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('crm_prospects');
    }
}
