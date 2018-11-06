<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function searchByParams($query_string, $values_to_add, $limit): array {
      $entityManager = $this->getEntityManager();
      file_put_contents('/var/www/mswydatki.pl/log.log', $query_string . "\n", FILE_APPEND);
      file_put_contents('/var/www/mswydatki.pl/log2.log', print_r($values_to_add, true) . "\n", FILE_APPEND);
      $query = $entityManager->createQuery($query_string);
      foreach($values_to_add as $key => $value) {
        $query->setParameter($key, $value);
      }
      if($limit) {
        $query->setMaxResults($limit);
      }
      return $query->execute();
    }

    public function searchAll($query_string, $values_to_add = null): array {
      $entityManager = $this->getEntityManager();
      file_put_contents('/var/www/mswydatki.pl/log.log', $query_string . "\n", FILE_APPEND);
      $query = $entityManager->createQuery($query_string);
      if(isset($values_to_add )) {
        foreach($values_to_add as $key => $value) {
          $query->setParameter($key, $value);
        }
      }
      return $query->execute();
    }

//    /**
//     * @return Expense[] Returns an array of Expense objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Expense
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
