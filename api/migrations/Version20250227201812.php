<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250227201812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create shared_user table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('shared_user');
        $table->addColumn('id', 'uuid', ['length' => 36]);
        $table->addColumn('email', 'string', ['length' => 180]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('roles', 'json');
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('shared_user');
    }
}
