<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250309123757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create onboarding_applications table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('onboarding_applications');
        $table->addColumn('id', 'guid');
        $table->addColumn('user_email', 'string');
        $table->addColumn('organization_registration_number', 'string');
        $table->addColumn('organization_name', 'string');
        $table->addColumn('status', 'string');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['status']);
        $table->addUniqueIndex(['user_email', 'organization_registration_number']);
        $table->addUniqueIndex(['organization_registration_number']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('onboarding_applications');
    }
}
