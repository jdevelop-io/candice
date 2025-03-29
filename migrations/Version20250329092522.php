<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250329092522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create messenger_messages table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('messenger_messages');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('body', 'text');
        $table->addColumn('headers', 'text');
        $table->addColumn('queue_name', 'string', ['length' => 190]);
        $table->addColumn('created_at', 'datetime_immutable');
        $table->addColumn('available_at', 'datetime_immutable');
        $table->addColumn('delivered_at', 'datetime_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['queue_name']);
        $table->addIndex(['created_at']);
        $table->addIndex(['available_at']);
        $table->addIndex(['delivered_at']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('messenger_messages');
    }
}
