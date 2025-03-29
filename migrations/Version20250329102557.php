<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250329102557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create onboarding_organizations table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('onboarding_organizations');
        $table->addColumn('registration_number', 'string', ['length' => 9]);
        $table->addColumn('registration_number_type', 'string', ['length' => 5]);
        $table->addColumn('name', 'string', ['length' => 45]);
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addColumn('updated_at', 'datetime_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['registration_number']);
        $table->addUniqueIndex(['registration_number_type', 'registration_number']);
        $table->addIndex(['registration_number_type']);
        $table->addIndex(['name']);
        $table->addIndex(['created_at']);
        $table->addIndex(['updated_at']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('onboarding_organizations');
    }
}
