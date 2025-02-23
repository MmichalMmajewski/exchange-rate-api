<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240411191803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE currency (
                    id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
                    name VARCHAR(255) NOT NULL, 
                    currency_code VARCHAR(32) NOT NULL, 
                    exchange_rate NUMERIC(10, 2) NOT NULL,                 
                    created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                    modified DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency');
    }
}
