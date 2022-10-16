<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015113939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD image_name VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(50) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE number_of_person nb_people INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP image_name, CHANGE name name VARCHAR(51) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE nb_people number_of_person INT DEFAULT NULL');
    }
}
