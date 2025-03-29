<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250329102835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create onboarding_enrollments table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('onboarding_enrollments');
        $table->addColumn('id', 'guid');
        $table->addColumn('applicant_id', 'string');
        $table->addColumn('organization_id', 'string');
        $table->addColumn('status', 'string', ['length' => 45]);
        $table->addColumn('processed_by', 'string', ['length' => 45, 'notnull' => false]);
        $table->addColumn('processed_at', 'datetime_immutable', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addColumn('updated_at', 'datetime_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['applicant_id', 'organization_id']);
        $table->addForeignKeyConstraint(
            'onboarding_applicants',
            ['applicant_id'],
            ['email'], ['onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            'onboarding_organizations',
            ['organization_id'],
            ['registration_number'],
            ['onDelete' => 'CASCADE']
        );
        $table->addIndex(['applicant_id']);
        $table->addIndex(['organization_id']);
        $table->addIndex(['status']);
        $table->addIndex(['processed_by']);
        $table->addIndex(['processed_at']);
        $table->addIndex(['created_at']);
        $table->addIndex(['updated_at']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('onboarding_enrollments');
    }
}
