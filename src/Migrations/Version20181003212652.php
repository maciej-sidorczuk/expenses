<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003212652 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE expense DROP total_price');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6512C695E237E06 ON category_of_expense (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B61A1F65E237E06 ON payment_method (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD4CAA325E237E06 ON type_of_expense (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_F6512C695E237E06 ON category_of_expense');
        $this->addSql('ALTER TABLE expense ADD total_price DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX UNIQ_7B61A1F65E237E06 ON payment_method');
        $this->addSql('DROP INDEX UNIQ_D34A04AD5E237E06 ON product');
        $this->addSql('DROP INDEX UNIQ_CD4CAA325E237E06 ON type_of_expense');
    }
}
