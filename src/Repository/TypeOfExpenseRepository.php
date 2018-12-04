<?php

namespace App\Repository;

use App\Entity\TypeOfExpense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeOfExpense|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfExpense|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfExpense[]    findAll()
 * @method TypeOfExpense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfExpenseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeOfExpense::class);
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
//     * @return TypeOfExpense[] Returns an array of TypeOfExpense objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOfExpense
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
