<?php

namespace App\Repository;

use App\Entity\CategoryOfExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryOfExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfExpense[]    findAll()
 * @method CategoryOfExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfExpenseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryOfExpense::class);
    }

    public function searchByString($query_string, $value = null): array {
      $entityManager = $this->getEntityManager();
      $query = $entityManager->createQuery($query_string);
      if(isset($value)) {
        $query->setParameter('name', $value);
      }
      return $query->execute();
    }

//    /**
//     * @return CategoryOfExpense[] Returns an array of CategoryOfExpense objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryOfExpense
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
