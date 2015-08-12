<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class Version20150813152914 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_metadata (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, `key` VARCHAR(255) NOT NULL, metadata LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_B0AF6FFB8A90ABA9 (`key`), INDEX IDX_B0AF6FFB727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_metadata ADD CONSTRAINT FK_B0AF6FFB727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_metadata (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_metadata DROP FOREIGN KEY FK_B0AF6FFB727ACA70');
        $this->addSql('DROP TABLE sylius_metadata');
    }
}
