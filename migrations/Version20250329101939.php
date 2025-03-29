<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250329101939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create onboarding_applicants table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('onboarding_applicants');
        $table->addColumn('email', 'string', ['length' => 180]);
        $table->addColumn('first_name', 'string', ['length' => 45]);
        $table->addColumn('last_name', 'string', ['length' => 45]);
        $table->addColumn('position', 'string', ['length' => 45]);
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addColumn('updated_at', 'datetime_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['email']);
        $table->addUniqueIndex(['email']);
        $table->addIndex(['position']);
        $table->addIndex(['created_at']);
        $table->addIndex(['updated_at']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('onboarding_applicants');
    }
}
