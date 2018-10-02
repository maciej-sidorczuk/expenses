<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181002183039 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, place_id_id INT DEFAULT NULL, payment_method_id_id INT NOT NULL, type_of_expense_id_id INT NOT NULL, category_of_expense_id_id INT NOT NULL, purchase_date DATE NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, quantity INT NOT NULL, total_price DOUBLE PRECISION NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_2D3A8DA6DE18E50B (product_id_id), INDEX IDX_2D3A8DA6D6328574 (place_id_id), INDEX IDX_2D3A8DA6A0CE293E (payment_method_id_id), INDEX IDX_2D3A8DA6CEECB1CC (type_of_expense_id_id), INDEX IDX_2D3A8DA61C4E89F9 (category_of_expense_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_of_expense (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_expense (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6D6328574 FOREIGN KEY (place_id_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A0CE293E FOREIGN KEY (payment_method_id_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6CEECB1CC FOREIGN KEY (type_of_expense_id_id) REFERENCES type_of_expense (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA61C4E89F9 FOREIGN KEY (category_of_expense_id_id) REFERENCES category_of_expense (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6DE18E50B');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6D6328574');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA61C4E89F9');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6A0CE293E');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6CEECB1CC');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE category_of_expense');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE type_of_expense');
    }
}
