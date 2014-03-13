<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140313212356 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE permission_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_BB4729B65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE permission_group_permission (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_B61B5A37FE54D947 (group_id), INDEX IDX_B61B5A37FED90CCA (permission_id), UNIQUE INDEX permission_group_permission_UNIQUE (group_id, permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE permission_group_permission ADD CONSTRAINT FK_B61B5A37FE54D947 FOREIGN KEY (group_id) REFERENCES permission_group (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE permission_group_permission ADD CONSTRAINT FK_B61B5A37FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE permission_group_permission DROP FOREIGN KEY FK_B61B5A37FE54D947");
        $this->addSql("DROP TABLE permission_group");
        $this->addSql("DROP TABLE permission_group_permission");
    }
}
