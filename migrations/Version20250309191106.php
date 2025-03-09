<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250309191106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create messenger_messages table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('messenger_messages');
        $table->addColumn('id', 'bigint', ['autoincrement' => true]);
        $table->addColumn('body', 'text');
        $table->addColumn('headers', 'text');
        $table->addColumn('queue_name', 'string');
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->addColumn('available_at', 'datetime', ['notnull' => true]);
        $table->addColumn('delivered_at', 'datetime', ['notnull' => false]);
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
