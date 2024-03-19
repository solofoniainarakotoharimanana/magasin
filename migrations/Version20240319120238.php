<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319120238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command_detail (id INT AUTO_INCREMENT NOT NULL, command_id INT NOT NULL, product_id INT NOT NULL, quantity_cmd INT NOT NULL, total_price DOUBLE PRECISION NOT NULL,  created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at_at DATETIME NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9145B6D033E1689A (command_id), INDEX IDX_9145B6D04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, code_cmd VARCHAR(255) NOT NULL, date_cmd DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',  created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at_at DATETIME NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_detail ADD CONSTRAINT FK_9145B6D033E1689A FOREIGN KEY (command_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE command_detail ADD CONSTRAINT FK_9145B6D04584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command_detail DROP FOREIGN KEY FK_9145B6D033E1689A');
        $this->addSql('ALTER TABLE command_detail DROP FOREIGN KEY FK_9145B6D04584665A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('DROP TABLE command_detail');
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE address ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE products ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
