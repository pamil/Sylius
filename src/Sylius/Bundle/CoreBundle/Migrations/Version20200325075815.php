<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200325075815 extends AbstractMigration
{
    public function up(Schema $schema): void
    {


        $this->addSql('ALTER TABLE sylius_taxon ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('UPDATE sylius_taxon SET enabled = 1');
    }

    public function down(Schema $schema): void
    {


        $this->addSql('ALTER TABLE sylius_taxon DROP enabled');
    }
}
