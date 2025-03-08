<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308214706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create iam_users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('iam_users');
        $table->addColumn('id', 'guid');
        $table->addColumn('email', 'string', ['length' => 180]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('roles', 'json');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('iam_users');
    }
}
